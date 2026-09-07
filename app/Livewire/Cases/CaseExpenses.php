<?php

namespace App\Livewire\Cases;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\LegalCase;
use App\Models\CaseExpense;
use App\Models\User;
use App\Notifications\ExpenseSubmittedNotification;
use Illuminate\Support\Facades\Storage;

class CaseExpenses extends Component
{
    use WithFileUploads;

    public LegalCase $case;

    // Form state
    public bool   $showForm  = false;
    public float  $amount    = 0;
    public string $category  = 'عام';
    public string $notes     = '';
    public $receipt          = null;

    public array $categories = [
        'رسوم قضائية',
        'نقل ومواصلات',
        'طباعة وتصوير',
        'خبراء وتقييم',
        'اتصالات',
        'عام',
    ];

    protected function rules(): array
    {
        return [
            'amount'   => 'required|numeric|min:0.01',
            'category' => 'required|string',
            'notes'    => 'nullable|string|max:1000',
            'receipt'  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
    }

    protected function messages(): array
    {
        return [
            'amount.required' => 'المبلغ مطلوب.',
            'amount.min'      => 'المبلغ يجب أن يكون أكبر من صفر.',
            'receipt.mimes'   => 'صيغ الإيصال المقبولة: pdf, jpg, png.',
            'receipt.max'     => 'حجم الإيصال لا يتجاوز 5 ميجابايت.',
        ];
    }

    public function mount(LegalCase $case): void
    {
        $this->case = $case;
    }

    public function submitExpense(): void
    {
        $user = auth()->user();

        // Lawyers and assistants can submit; admin can too
        $this->validate();

        $receiptPath = null;
        if ($this->receipt) {
            $receiptPath = $this->receipt->store('expense_receipts', 'public');
        }

        $expense = CaseExpense::create([
            'case_id'      => $this->case->id,
            'user_id'      => $user->id,
            'amount'       => $this->amount,
            'category'     => $this->category,
            'notes'        => $this->notes ?: null,
            'receipt_path' => $receiptPath,
            'status'       => 'pending',
        ]);

        // Notify all admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new ExpenseSubmittedNotification($expense));
        }

        $this->reset(['amount', 'category', 'notes', 'receipt', 'showForm']);
        $this->category = 'عام';
        session()->flash('expense_success', 'تم تقديم طلب المصروف بنجاح وهو بانتظار موافقة الإدارة.');
    }

    public function approveExpense(int $expenseId): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $expense = CaseExpense::findOrFail($expenseId);
        $expense->update(['status' => 'approved', 'approved_by' => auth()->id()]);

        session()->flash('expense_success', 'تمت الموافقة على المصروف بنجاح.');
    }

    public function rejectExpense(int $expenseId): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $expense = CaseExpense::findOrFail($expenseId);
        $expense->update(['status' => 'rejected', 'approved_by' => auth()->id()]);

        session()->flash('expense_success', 'تم رفض طلب المصروف.');
    }

    public function deleteExpense(int $expenseId): void
    {
        $user    = auth()->user();
        $expense = CaseExpense::findOrFail($expenseId);

        // Only the submitter (if pending) or admin can delete
        abort_unless(
            $user->isAdmin() || ($expense->user_id === $user->id && $expense->isPending()),
            403
        );

        if ($expense->receipt_path && Storage::disk('public')->exists($expense->receipt_path)) {
            Storage::disk('public')->delete($expense->receipt_path);
        }

        $expense->delete();
        session()->flash('expense_success', 'تم حذف طلب المصروف.');
    }

    public function render()
    {
        $expenses = CaseExpense::where('case_id', $this->case->id)
            ->with(['submittedBy', 'approvedBy'])
            ->latest()
            ->get();

        $totalApproved = $expenses->where('status', 'approved')->sum('amount');

        return view('livewire.cases.case-expenses', compact('expenses', 'totalApproved'));
    }
}
