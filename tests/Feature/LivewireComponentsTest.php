<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\LegalCase;
use App\Models\Client;
use App\Models\Court;
use App\Models\Jurisdiction;
use App\Models\CourtLevel;
use App\Models\Lawyer;
use App\Models\Setting;
use App\Models\Appointment;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Livewire\Notifications\NotificationBell;
use App\Livewire\Notifications\NotificationIndex;
use App\Livewire\Settings\SettingsEdit;
use App\Livewire\Cases\CaseIndex;
use App\Livewire\Cases\CaseCreate;
use App\Livewire\Cases\CaseShow;
use App\Livewire\Cases\CaseEdit;
use App\Livewire\Appointments\AppointmentIndex;
use App\Livewire\Appointments\AppointmentCreate;
use App\Livewire\Appointments\AppointmentNotifications;
use App\Livewire\Clients\ClientIndex;
use App\Livewire\Lawyers\LawyerIndex;
use App\Livewire\Lawyers\LawyerCreate;
use App\Livewire\Courts\CourtIndex;
use App\Livewire\Jurisdictions\JurisdictionIndex;
use App\Livewire\Documents\DocumentIndex;
use App\Livewire\Contracts\ContractIndex;
use App\Notifications\CaseAssignedNotification;

class LivewireComponentsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Jurisdiction $jurisdiction;
    protected Court $court;
    protected CourtLevel $courtLevel;
    protected Lawyer $lawyer;
    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name'     => 'Super Admin',
            'email'    => 'admin@elmizzan.test',
            'password' => bcrypt('secret123'),
            'role'     => 'admin',
        ]);

        Setting::set('app_name', 'الميزان');

        $this->jurisdiction = Jurisdiction::create([
            'name' => 'القضاء العادي',
        ]);

        $this->courtLevel = CourtLevel::create([
            'name' => 'ابتدائي',
        ]);

        $this->court = Court::create([
            'name'            => 'محكمة القاهرة الابتدائية',
            'jurisdiction_id' => $this->jurisdiction->id,
        ]);

        $this->lawyer = Lawyer::create([
            'name'           => 'أحمد محمود المحامي',
            'email'          => 'lawyer@test.com',
            'phone'          => '01012345678',
            'specialization' => 'مدني',
            'license_number' => '12345',
            'address'        => 'القاهرة',
            'degree'         => 'استئناف',
            'bio'            => 'محامي بالاستئناف العالي ومجلس الدولة خبرة واسعة',
        ]);

        $this->client = Client::create([
            'name'    => 'محمد علي الموكل',
            'phone'   => '01099998888',
            'email'   => 'client@test.com',
            'address' => 'القاهرة، مصر',
            'nid'     => '12345678901234',
        ]);
    }

    public function test_settings_edit_livewire_component_updates_firm_name_reactively()
    {
        Livewire::actingAs($this->admin)
            ->test(SettingsEdit::class)
            ->assertSet('app_name', 'الميزان')
            ->set('app_name', 'مكتب النخبة للمحاماة')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSee('تم حفظ وتحديث إعدادات المكتب بنجاح');

        $this->assertEquals('مكتب النخبة للمحاماة', Setting::get('app_name'));
        $this->assertEquals('مكتب النخبة للمحاماة', firm_name());
    }

    public function test_non_admin_cannot_access_settings_edit_livewire_component()
    {
        $lawyerUser = User::factory()->create(['role' => 'lawyer', 'lawyer_id' => $this->lawyer->id]);

        Livewire::actingAs($lawyerUser)
            ->test(SettingsEdit::class)
            ->assertStatus(403);
    }

    public function test_notification_bell_displays_and_marks_notifications()
    {
        $case = LegalCase::create([
            'case_number'   => 'CASE-LIVEWIRE-1',
            'status'        => 'مفتوحة',
            'rival_name'    => 'خصم تجريبي',
            'rival_number'  => '01000000000',
            'rival_address' => 'عنوان الخصم',
            'rival_nid'     => '12345678901234',
            'court_id'      => $this->court->id,
            'lawyer_id'     => $this->lawyer->id,
        ]);

        $this->admin->notify(new CaseAssignedNotification($case, 'lead'));

        $this->assertEquals(1, $this->admin->unreadNotifications()->count());

        $notificationId = $this->admin->unreadNotifications()->first()->id;

        Livewire::actingAs($this->admin)
            ->test(NotificationBell::class)
            ->assertSee('1')
            ->assertSee('CASE-LIVEWIRE-1')
            ->call('markAsRead', $notificationId);

        $this->assertEquals(0, $this->admin->fresh()->unreadNotifications()->count());
    }

    public function test_notification_index_tabs_and_mark_all_read()
    {
        $case = LegalCase::create([
            'case_number'   => 'CASE-LIVEWIRE-2',
            'status'        => 'مفتوحة',
            'rival_name'    => 'خصم 2',
            'rival_number'  => '01000000000',
            'rival_address' => 'عنوان 2',
            'rival_nid'     => '12345678901235',
            'court_id'      => $this->court->id,
            'lawyer_id'     => $this->lawyer->id,
        ]);

        $this->admin->notify(new CaseAssignedNotification($case, 'lead'));
        $this->admin->notify(new CaseAssignedNotification($case, 'assistant'));

        $this->assertEquals(2, $this->admin->unreadNotifications()->count());

        Livewire::actingAs($this->admin)
            ->test(NotificationIndex::class)
            ->assertSet('filter', 'all')
            ->assertSee('CASE-LIVEWIRE-2')
            ->set('filter', 'unread')
            ->assertSee('CASE-LIVEWIRE-2')
            ->call('markAllAsRead');

        $this->assertEquals(0, $this->admin->fresh()->unreadNotifications()->count());
    }

    public function test_case_index_filtering_and_deletion()
    {
        $caseA = LegalCase::create([
            'case_number'   => 'CASE-ALPHA-100',
            'status'        => 'مفتوحة',
            'rival_name'    => 'الخصم أ',
            'rival_number'  => '01000000001',
            'rival_address' => 'القاهرة',
            'rival_nid'     => '11111111111111',
            'court_id'      => $this->court->id,
            'lawyer_id'     => $this->lawyer->id,
        ]);

        $caseB = LegalCase::create([
            'case_number'   => 'CASE-BETA-200',
            'status'        => 'منتهية',
            'rival_name'    => 'الخصم ب',
            'rival_number'  => '01000000002',
            'rival_address' => 'الجيزة',
            'rival_nid'     => '22222222222222',
            'court_id'      => $this->court->id,
            'lawyer_id'     => $this->lawyer->id,
        ]);

        Livewire::actingAs($this->admin)
            ->test(CaseIndex::class)
            ->assertSee('CASE-ALPHA-100')
            ->assertSee('CASE-BETA-200')
            ->set('search', 'ALPHA')
            ->assertSee('CASE-ALPHA-100')
            ->assertDontSee('CASE-BETA-200')
            ->set('search', '')
            ->set('statusFilter', 'منتهية')
            ->assertSee('CASE-BETA-200')
            ->assertDontSee('CASE-ALPHA-100')
            ->call('deleteCase', $caseB->id);

        $this->assertDatabaseMissing('cases', ['id' => $caseB->id]);
    }

    public function test_case_create_component_creates_case_and_redirects()
    {
        Livewire::actingAs($this->admin)
            ->test(CaseCreate::class)
            ->assertStatus(200)
            ->set('client_id', $this->client->id)
            ->set('case_number', 'CASE-NEW-999')
            ->set('status', 'مفتوحة')
            ->set('jurisdiction_id', $this->jurisdiction->id)
            ->set('court_level_id', $this->courtLevel->id)
            ->set('court_id', $this->court->id)
            ->set('lawyer_id', $this->lawyer->id)
            ->set('rival_name', 'الخصم التجريبي الجديد')
            ->set('rival_number', '01011112222')
            ->set('rival_address', 'شارع قصر العيني، القاهرة')
            ->set('rival_nid', '12345678901234')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('cases.index'));

        $this->assertDatabaseHas('cases', ['case_number' => 'CASE-NEW-999']);
    }

    public function test_case_show_quick_status_update()
    {
        $case = LegalCase::create([
            'case_number'   => 'CASE-SHOW-1',
            'status'        => 'مفتوحة',
            'rival_name'    => 'خصم تجربة',
            'rival_number'  => '01000000003',
            'rival_address' => 'الإسكندرية',
            'rival_nid'     => '33333333333333',
            'court_id'      => $this->court->id,
            'lawyer_id'     => $this->lawyer->id,
        ]);

        Livewire::actingAs($this->admin)
            ->test(CaseShow::class, ['case' => $case])
            ->assertSee('CASE-SHOW-1')
            ->call('updateStatus', 'محجوزة للحكم')
            ->assertSee('محجوزة للحكم');

        $this->assertEquals('محجوزة للحكم', $case->fresh()->status);
    }

    public function test_appointment_index_and_deletion()
    {
        $case = LegalCase::create([
            'case_number'   => 'CASE-APPT-1',
            'status'        => 'مفتوحة',
            'rival_name'    => 'خصم موعد',
            'rival_number'  => '01000000004',
            'rival_address' => 'طنطا',
            'rival_nid'     => '44444444444444',
            'court_id'      => $this->court->id,
            'lawyer_id'     => $this->lawyer->id,
        ]);

        $appt = Appointment::create([
            'case_id' => $case->id,
            'date'    => now()->addDay()->toDateString(),
            'time'    => '10:00',
            'notes'   => 'جلسة استماع أولى',
        ]);

        Livewire::actingAs($this->admin)
            ->test(AppointmentIndex::class)
            ->assertSee('جلسة استماع أولى')
            ->assertSee('CASE-APPT-1')
            ->call('delete', $appt->id)
            ->assertSee('تم حذف الموعد بنجاح');

        $this->assertDatabaseMissing('appointments', ['id' => $appt->id]);
    }

    public function test_appointment_notifications_component_renders()
    {
        Livewire::actingAs($this->admin)
            ->test(AppointmentNotifications::class)
            ->assertStatus(200)
            ->assertSee('الإشعارات والمواعيد');
    }

    public function test_clients_and_lawyers_livewire_components_render()
    {
        Livewire::actingAs($this->admin)
            ->test(ClientIndex::class)
            ->assertStatus(200);

        Livewire::actingAs($this->admin)
            ->test(LawyerIndex::class)
            ->assertStatus(200);

        Livewire::actingAs($this->admin)
            ->test(CourtIndex::class)
            ->assertStatus(200);

        Livewire::actingAs($this->admin)
            ->test(JurisdictionIndex::class)
            ->assertStatus(200);

        Livewire::actingAs($this->admin)
            ->test(DocumentIndex::class)
            ->assertStatus(200);

        Livewire::actingAs($this->admin)
            ->test(ContractIndex::class)
            ->assertStatus(200);
    }
}
