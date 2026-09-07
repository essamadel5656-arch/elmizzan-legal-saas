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
use App\Models\Setting;
use Livewire\Livewire;
use App\Livewire\Dashboard\Index as DashboardIndex;
use App\Livewire\Cases\CaseEdit;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FinancialPermissionsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $lawyerUser;
    protected Lawyer $lawyer;
    protected Client $client;
    protected LegalCase $case;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::set('app_name', 'الميزان');

        $jurisdiction = Jurisdiction::create(['name' => 'القضاء المدني']);
        $courtLevel   = CourtLevel::create(['name' => 'ابتدائي']);
        $court        = Court::create([
            'name'            => 'محكمة القاهرة',
            'jurisdiction_id' => $jurisdiction->id,
        ]);

        $this->lawyer = Lawyer::create([
            'name'           => 'أ/ علي كمال',
            'phone'          => '01011112222',
            'email'          => 'ali@elmizzan.test',
            'specialization' => 'مدني',
            'license_number' => '100200',
            'address'        => 'القاهرة',
            'degree'         => 'ابتدائي',
            'bio'            => 'محامٍ بالنقض والاستئناف العالي ذو خبرة واسعة',
        ]);

        $this->admin = User::create([
            'name'     => 'المدير العام',
            'email'    => 'admin@elmizzan.test',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        $this->lawyerUser = User::create([
            'name'      => 'أ/ علي كمال',
            'email'     => 'lawyer.ali@elmizzan.test',
            'password'  => bcrypt('password'),
            'role'      => 'lawyer',
            'lawyer_id' => $this->lawyer->id,
        ]);

        $this->client = Client::create([
            'name'    => 'محمود أحمد',
            'email'   => 'mahmoud@test.com',
            'phone'   => '01033334444',
            'nid'     => '29001010101010',
            'address' => 'الجيزة',
        ]);

        $this->case = LegalCase::create([
            'case_number'        => 'CASE-101',
            'status'             => 'مفتوحة',
            'jurisdiction_id'    => $jurisdiction->id,
            'court_level_id'     => $courtLevel->id,
            'court_id'           => $court->id,
            'lawyer_id'          => $this->lawyer->id,
            'total_costs'        => 50000,
            'deposit'            => 20000,
            'agreed_legal_fee'   => 45000,
            'rival_name'         => 'شركة الأفق',
            'rival_number'       => '01055556666',
            'rival_address'      => 'القاهرة',
            'rival_nid'          => '28001010101010',
            'Previous_procedure' => 'لا يوجد',
            'final_decision'     => 'قيد النظر',
            'notes'              => 'ملاحظات',
        ]);

        $this->case->clients()->attach($this->client->id);
        $this->case->lawyers()->attach($this->lawyer->id, ['role' => 'lead']);
    }

    public function test_lawyer_cannot_see_profit_metrics_on_dashboard(): void
    {
        Livewire::actingAs($this->lawyerUser)
            ->test(DashboardIndex::class)
            ->assertDontSee('إجمالي الأتعاب')
            ->assertDontSee('نسبة التحصيل');
    }

    public function test_admin_can_see_profit_metrics(): void
    {
        Livewire::actingAs($this->admin)
            ->test(DashboardIndex::class)
            ->assertSee('إجمالي الأتعاب')
            ->assertSee('نسبة التحصيل')
            ->assertSee('50,000');
    }

    public function test_lawyer_cannot_set_agreed_legal_fee(): void
    {
        Livewire::actingAs($this->lawyerUser)
            ->test(CaseEdit::class, ['case' => $this->case])
            ->set('agreed_legal_fee', 80000)
            ->call('save')
            ->assertForbidden();

        $this->case->refresh();
        $this->assertEquals(45000, $this->case->agreed_legal_fee);
    }

    public function test_admin_can_set_agreed_legal_fee(): void
    {
        Livewire::actingAs($this->admin)
            ->test(CaseEdit::class, ['case' => $this->case])
            ->set('agreed_legal_fee', 60000)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('cases.show', $this->case->id));

        $this->case->refresh();
        $this->assertEquals(60000, $this->case->agreed_legal_fee);
    }

    public function test_admin_dashboard_renders_pending_expenses_with_user_relationship(): void
    {
        \App\Models\CaseExpense::create([
            'case_id'     => $this->case->id,
            'user_id'     => $this->admin->id,
            'amount'      => 1500,
            'category'    => 'رسوم قضائية',
            'status'      => 'pending',
            'notes'       => 'مصروفات استخراج شهادات',
        ]);

        Livewire::actingAs($this->admin)
            ->test(DashboardIndex::class)
            ->assertStatus(200)
            ->assertSee('طلبات المصاريف بانتظار الموافقة')
            ->assertSee('1,500')
            ->assertSee($this->admin->name);
    }
}
