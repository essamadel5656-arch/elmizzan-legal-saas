<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\LegalCase;
use App\Models\Client;
use App\Models\Lawyer;
use App\Models\Court;
use App\Models\Jurisdiction;
use App\Models\CourtLevel;
use App\Models\DocumentRequest;
use App\Models\Setting;
use App\Notifications\DocumentUploadedNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use App\Livewire\Cases\DocumentRequests;
use App\Livewire\ClientPortal\DocumentUpload;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DocumentRequestLifecycleTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $clientUser;
    protected Lawyer $lawyer;
    protected User $lawyerUser;
    protected Client $client;
    protected LegalCase $case;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::set('app_name', 'الميزان');
        Storage::fake('public');

        $jurisdiction = Jurisdiction::create(['name' => 'القضاء العادي']);
        $courtLevel   = CourtLevel::create(['name' => 'ابتدائية']);
        $court        = Court::create([
            'name'            => 'محكمة مسقط',
            'jurisdiction_id' => $jurisdiction->id,
        ]);

        $this->lawyer = Lawyer::create([
            'name'           => 'أ/ هلال الحارثي',
            'phone'          => '95556666',
            'email'          => 'hilal@elmizzan.test',
            'specialization' => 'قضايا عمالية',
            'license_number' => 'OM-3344',
            'address'        => 'مسقط',
            'degree'         => 'استئناف',
            'bio'            => 'محامٍ بالاستئناف',
        ]);

        $this->admin = User::create([
            'name'     => 'المدير الإداري',
            'email'    => 'admin.doc@elmizzan.test',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        $this->lawyerUser = User::create([
            'name'      => 'أ/ هلال الحارثي',
            'email'     => 'lawyer.hilal@elmizzan.test',
            'password'  => bcrypt('password'),
            'role'      => 'lawyer',
            'lawyer_id' => $this->lawyer->id,
        ]);

        $this->client = Client::create([
            'name'    => 'طارق الشكيلي',
            'email'   => 'tariq@client.test',
            'phone'   => '96667777',
            'nid'     => '44332211',
            'address' => 'مطرح، مسقط',
        ]);

        $this->clientUser = User::create([
            'name'      => 'طارق الشكيلي',
            'email'     => 'tariq@client.test',
            'password'  => bcrypt('password'),
            'role'      => 'client',
            'client_id' => $this->client->id,
        ]);

        $this->case = LegalCase::create([
            'case_number'        => 'DOC-CASE-001',
            'status'             => 'مفتوحة',
            'jurisdiction_id'    => $jurisdiction->id,
            'court_level_id'     => $courtLevel->id,
            'court_id'           => $court->id,
            'lawyer_id'          => $this->lawyer->id,
            'total_costs'        => 4000,
            'deposit'            => 1500,
            'agreed_legal_fee'   => 4000,
            'rival_name'         => 'مؤسسة النور',
            'rival_number'       => '97778888',
            'rival_address'      => 'مسقط',
            'rival_nid'          => '33221100',
            'Previous_procedure' => 'لا يوجد',
            'final_decision'     => 'قيد النظر',
            'notes'              => 'ملاحظات',
        ]);

        $this->case->clients()->attach($this->client->id);
        $this->case->lawyers()->attach($this->lawyer->id, ['role' => 'lead']);
    }

    public function test_lawyer_can_create_document_request(): void
    {
        Livewire::actingAs($this->lawyerUser)
            ->test(DocumentRequests::class, ['case' => $this->case])
            ->set('title', 'صورة من عقد العمل الأصلي')
            ->set('description', 'يرجى تزويدنا بالعقد متضمناً كافة الشروط والبدلات')
            ->set('client_id', $this->client->id)
            ->call('createRequest')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('document_requests', [
            'case_id'      => $this->case->id,
            'client_id'    => $this->client->id,
            'requested_by' => $this->lawyerUser->id,
            'title'        => 'صورة من عقد العمل الأصلي',
            'status'       => 'pending',
        ]);
    }

    public function test_client_can_upload_document(): void
    {
        $docRequest = DocumentRequest::create([
            'case_id'      => $this->case->id,
            'client_id'    => $this->client->id,
            'requested_by' => $this->lawyerUser->id,
            'title'        => 'كشف حساب بنكي لآخر 6 أشهر',
            'status'       => 'pending',
        ]);

        $fakeFile = UploadedFile::fake()->create('bank_statement.pdf', 500, 'application/pdf');

        Livewire::actingAs($this->clientUser)
            ->test(DocumentUpload::class, ['documentRequest' => $docRequest])
            ->set('file', $fakeFile)
            ->call('upload')
            ->assertHasNoErrors()
            ->assertRedirect(route('client-portal.dashboard'));

        $docRequest->refresh();
        $this->assertEquals('uploaded', $docRequest->status);
        $this->assertNotNull($docRequest->file_path);
        $this->assertNotNull($docRequest->uploaded_at);
        Storage::disk('public')->assertExists($docRequest->file_path);
    }

    public function test_upload_notifies_lead_lawyer(): void
    {
        Notification::fake();

        $docRequest = DocumentRequest::create([
            'case_id'      => $this->case->id,
            'client_id'    => $this->client->id,
            'requested_by' => $this->lawyerUser->id,
            'title'        => 'البطاقة المدنية للمدعي',
            'status'       => 'pending',
        ]);

        $fakeFile = UploadedFile::fake()->create('id_card.png', 200, 'image/png');

        Livewire::actingAs($this->clientUser)
            ->test(DocumentUpload::class, ['documentRequest' => $docRequest])
            ->set('file', $fakeFile)
            ->call('upload')
            ->assertHasNoErrors();

        Notification::assertSentTo(
            [$this->lawyerUser, $this->admin],
            DocumentUploadedNotification::class
        );
    }

    public function test_document_request_status_transitions(): void
    {
        // 1. Created -> pending
        $docRequest = DocumentRequest::create([
            'case_id'      => $this->case->id,
            'client_id'    => $this->client->id,
            'requested_by' => $this->lawyerUser->id,
            'title'        => 'عقد إيجار المحل التجاري',
            'status'       => 'pending',
        ]);
        $this->assertEquals('pending', $docRequest->status);

        // 2. Client uploads -> uploaded
        $fakeFile = UploadedFile::fake()->create('lease.pdf', 300, 'application/pdf');
        Livewire::actingAs($this->clientUser)
            ->test(DocumentUpload::class, ['documentRequest' => $docRequest])
            ->set('file', $fakeFile)
            ->call('upload')
            ->assertHasNoErrors();

        $docRequest->refresh();
        $this->assertEquals('uploaded', $docRequest->status);

        // 3. Lawyer approves -> approved
        Livewire::actingAs($this->lawyerUser)
            ->test(DocumentRequests::class, ['case' => $this->case])
            ->call('approveDocument', $docRequest->id)
            ->assertHasNoErrors();

        $docRequest->refresh();
        $this->assertEquals('approved', $docRequest->status);
    }
}
