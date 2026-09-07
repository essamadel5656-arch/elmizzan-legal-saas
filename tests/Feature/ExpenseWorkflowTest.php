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
use App\Models\CaseExpense;
use App\Models\Setting;
use Livewire\Livewire;
use App\Livewire\Cases\CaseExpenses;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExpenseWorkflowTest extends TestCase
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

        $jurisdiction = Jurisdiction::create(['name' => 'القضاء العادي']);
        $courtLevel   = CourtLevel::create(['name' => 'ابتدائية']);
        $court        = Court::create([
            'name'            => 'محكمة مسقط',
            'jurisdiction_id' => $jurisdiction->id,
        ]);

        $this->lawyer = Lawyer::create([
            'name'           => 'أ/ سالم الراشدي',
            'phone'          => '91234567',
            'email'          => 'salem@elmizzan.test',
            'specialization' => 'تجاري',
            'license_number' => 'OM-8877',
            'address'        => 'مسقط',
            'degree'         => 'ابتدائي',
            'bio'            => 'محامٍ ومستشار قانوني',
        ]);

        $this->admin = User::create([
            'name'     => 'المدير المسؤول',
            'email'    => 'admin@elmizzan.test',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        $this->lawyerUser = User::create([
            'name'      => 'أ/ سالم الراشدي',
            'email'     => 'lawyer.salem@elmizzan.test',
            'password'  => bcrypt('password'),
            'role'      => 'lawyer',
            'lawyer_id' => $this->lawyer->id,
        ]);

        $this->client = Client::create([
            'name'    => 'خالد بن ناصر',
            'email'   => 'khaled@client.test',
            'phone'   => '99887766',
            'nid'     => '12345678',
            'address' => 'بوشر، مسقط',
        ]);

        $this->case = LegalCase::create([
            'case_number'        => 'EXP-CASE-001',
            'status'             => 'مفتوحة',
            'jurisdiction_id'    => $jurisdiction->id,
            'court_level_id'     => $courtLevel->id,
            'court_id'           => $court->id,
            'lawyer_id'          => $this->lawyer->id,
            'total_costs'        => 5000,
            'deposit'            => 2000,
            'agreed_legal_fee'   => 5000,
            'rival_name'         => 'شركة الإنماء',
            'rival_number'       => '91112222',
            'rival_address'      => 'مسقط',
            'rival_nid'          => '88776655',
            'Previous_procedure' => 'لا يوجد',
            'final_decision'     => 'قيد النظر',
            'notes'              => 'ملاحظات',
        ]);

        $this->case->clients()->attach($this->client->id);
        $this->case->lawyers()->attach($this->lawyer->id, ['role' => 'lead']);
    }

    public function test_lawyer_can_submit_expense(): void
    {
        Livewire::actingAs($this->lawyerUser)
            ->test(CaseExpenses::class, ['case' => $this->case])
            ->set('amount', 150.50)
            ->set('category', 'رسوم قضائية')
            ->set('notes', 'رسوم رفع الدعوى في المحكمة')
            ->call('submitExpense')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('case_expenses', [
            'case_id'  => $this->case->id,
            'user_id'  => $this->lawyerUser->id,
            'amount'   => 150.50,
            'category' => 'رسوم قضائية',
            'status'   => 'pending',
        ]);
    }

    public function test_expense_defaults_to_pending(): void
    {
        $expense = CaseExpense::create([
            'case_id'  => $this->case->id,
            'user_id'  => $this->lawyerUser->id,
            'amount'   => 200,
            'category' => 'نقل ومواصلات',
            'notes'    => 'مصاريف انتقال لحضور الجلسة',
            'status'   => 'pending',
        ]);

        $this->assertEquals('pending', $expense->status);
        $this->assertNull($expense->approved_by);
    }

    public function test_admin_can_approve_expense(): void
    {
        $expense = CaseExpense::create([
            'case_id'  => $this->case->id,
            'user_id'  => $this->lawyerUser->id,
            'amount'   => 300,
            'category' => 'خبراء وتقييم',
            'notes'    => 'أتعاب خبير عقاري معتمد',
            'status'   => 'pending',
        ]);

        Livewire::actingAs($this->admin)
            ->test(CaseExpenses::class, ['case' => $this->case])
            ->call('approveExpense', $expense->id)
            ->assertHasNoErrors();

        $expense->refresh();
        $this->assertEquals('approved', $expense->status);
        $this->assertEquals($this->admin->id, $expense->approved_by);
    }

    public function test_admin_can_reject_expense(): void
    {
        $expense = CaseExpense::create([
            'case_id'  => $this->case->id,
            'user_id'  => $this->lawyerUser->id,
            'amount'   => 500,
            'category' => 'اتصالات',
            'notes'    => 'فاتورة هاتف غير مبررة',
            'status'   => 'pending',
        ]);

        Livewire::actingAs($this->admin)
            ->test(CaseExpenses::class, ['case' => $this->case])
            ->call('rejectExpense', $expense->id)
            ->assertHasNoErrors();

        $expense->refresh();
        $this->assertEquals('rejected', $expense->status);
        $this->assertEquals($this->admin->id, $expense->approved_by);
    }

    public function test_only_approved_expenses_deduct_balance(): void
    {
        CaseExpense::create([
            'case_id'     => $this->case->id,
            'user_id'     => $this->lawyerUser->id,
            'amount'      => 100,
            'category'    => 'طباعة وتصوير',
            'status'      => 'approved',
            'approved_by' => $this->admin->id,
        ]);

        CaseExpense::create([
            'case_id'  => $this->case->id,
            'user_id'  => $this->lawyerUser->id,
            'amount'   => 400,
            'category' => 'خبراء وتقييم',
            'status'   => 'pending',
        ]);

        CaseExpense::create([
            'case_id'     => $this->case->id,
            'user_id'     => $this->lawyerUser->id,
            'amount'      => 250,
            'category'    => 'عام',
            'status'      => 'rejected',
            'approved_by' => $this->admin->id,
        ]);

        $this->assertEquals(3, $this->case->expenses()->count());
        $this->assertEquals(1, $this->case->approvedExpenses()->count());
        $this->assertEquals(100, (float) $this->case->approvedExpenses()->sum('amount'));
    }
}
