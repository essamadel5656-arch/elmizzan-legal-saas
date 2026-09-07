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
use App\Models\ClientPayment;
use App\Models\Appointment;
use App\Models\Setting;
use App\Notifications\InPersonPaymentScheduledNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use App\Livewire\Payments\ClientPaymentCreate;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentWorkflowTest extends TestCase
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
        Setting::set('bank_name', 'بنك مسقط');
        Setting::set('bank_iban', 'OM1234567890');

        $jurisdiction = Jurisdiction::create(['name' => 'القضاء العادي']);
        $courtLevel   = CourtLevel::create(['name' => 'ابتدائية']);
        $court        = Court::create([
            'name'            => 'محكمة السيب',
            'jurisdiction_id' => $jurisdiction->id,
        ]);

        $this->lawyer = Lawyer::create([
            'name'           => 'أ/ ماجد اليعربي',
            'phone'          => '92223333',
            'email'          => 'majed@elmizzan.test',
            'specialization' => 'مدني',
            'license_number' => 'OM-1122',
            'address'        => 'مسقط',
            'degree'         => 'استئناف',
            'bio'            => 'محامٍ بالاستئناف',
        ]);

        $this->admin = User::create([
            'name'     => 'المدير المالي',
            'email'    => 'admin.finance@elmizzan.test',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        $this->lawyerUser = User::create([
            'name'      => 'أ/ ماجد اليعربي',
            'email'     => 'lawyer.majed@elmizzan.test',
            'password'  => bcrypt('password'),
            'role'      => 'lawyer',
            'lawyer_id' => $this->lawyer->id,
        ]);

        $this->client = Client::create([
            'name'    => 'فيصل المعولي',
            'email'   => 'faisal@client.test',
            'phone'   => '93334444',
            'nid'     => '98765432',
            'address' => 'السيب، مسقط',
        ]);

        $this->clientUser = User::create([
            'name'      => 'فيصل المعولي',
            'email'     => 'faisal@client.test',
            'password'  => bcrypt('password'),
            'role'      => 'client',
            'client_id' => $this->client->id,
        ]);

        $this->case = LegalCase::create([
            'case_number'        => 'PAY-CASE-001',
            'status'             => 'مفتوحة',
            'jurisdiction_id'    => $jurisdiction->id,
            'court_level_id'     => $courtLevel->id,
            'court_id'           => $court->id,
            'lawyer_id'          => $this->lawyer->id,
            'total_costs'        => 3000,
            'deposit'            => 1000,
            'agreed_legal_fee'   => 3000,
            'rival_name'         => 'شركة الفجر',
            'rival_number'       => '94445555',
            'rival_address'      => 'مسقط',
            'rival_nid'          => '55443322',
            'Previous_procedure' => 'لا يوجد',
            'final_decision'     => 'قيد النظر',
            'notes'              => 'ملاحظات',
        ]);

        $this->case->clients()->attach($this->client->id);
        $this->case->lawyers()->attach($this->lawyer->id, ['role' => 'lead']);
    }

    public function test_in_person_payment_creates_appointment(): void
    {
        $targetDate = now()->addDays(2)->toDateString();

        Livewire::actingAs($this->clientUser)
            ->test(ClientPaymentCreate::class, ['case' => $this->case])
            ->set('mode', 'in_person')
            ->set('amount', 500)
            ->set('payment_date', $targetDate)
            ->set('notes', 'سداد دفعة نقدية في مكتب المحامي')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertRedirect(route('client-portal.dashboard'));

        $this->assertDatabaseHas('appointments', [
            'case_id'   => $this->case->id,
            'client_id' => $this->client->id,
            'date'      => $targetDate,
        ]);

        $this->assertDatabaseHas('client_payments', [
            'case_id'   => $this->case->id,
            'client_id' => $this->client->id,
            'mode'      => 'in_person',
            'amount'    => 500,
            'status'    => 'pending',
        ]);
    }

    public function test_in_person_payment_dispatches_notification(): void
    {
        Notification::fake();

        $targetDate = now()->addDay()->toDateString();

        Livewire::actingAs($this->clientUser)
            ->test(ClientPaymentCreate::class, ['case' => $this->case])
            ->set('mode', 'in_person')
            ->set('amount', 750)
            ->set('payment_date', $targetDate)
            ->call('submit')
            ->assertHasNoErrors();

        Notification::assertSentTo(
            [$this->lawyerUser, $this->admin],
            InPersonPaymentScheduledNotification::class
        );
    }

    public function test_transfer_payment_lands_in_pending_verification(): void
    {
        Livewire::actingAs($this->clientUser)
            ->test(ClientPaymentCreate::class, ['case' => $this->case])
            ->set('mode', 'transfer')
            ->set('amount', 1200)
            ->set('reference', 'BANK-TXN-998877')
            ->set('notes', 'تم التحويل عبر تطبيق بنك مسقط')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertRedirect(route('client-portal.dashboard'));

        $this->assertDatabaseHas('client_payments', [
            'case_id'          => $this->case->id,
            'client_id'        => $this->client->id,
            'mode'             => 'transfer',
            'amount'           => 1200,
            'reference_number' => 'BANK-TXN-998877',
            'status'           => 'pending_verification',
        ]);
    }
}
