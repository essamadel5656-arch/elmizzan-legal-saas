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
    public bool   $isModalOpen = false;
    public ?int   $confirmingDeleteId = null;
    public float  $amount    = 0;
    public string $category  = 'عام';
    public string $description = '';
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
            'amount'      => 'required|numeric|min:0.01',
            'category'    => 'required|string',
            'description' => 'required|string|max:1000',
            'receipt'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
    }

    protected function messages(): array
    {
        return [
            'amount.required' => 'المبلغ مطلوب.',
            'amount.min'      => 'المبلغ يجب أن يكون أكبر من صفر.',
            'description.required' => 'البيان / الوصف مطلوب.',
            'receipt.mimes'   => 'صيغ الإيصال المقبولة: pdf, jpg, png.',
            'receipt.max'     => 'حجم الإيصال لا يتجاوز 5 ميجابايت.',
        ];
    }

    public function mount(LegalCase $case): void
    {
        $this->case = $case;
    }

    public function openModal(): void
    {
        $this->reset(['amount', 'category', 'description', 'receipt']);
        $this->category = 'عام';
        $this->isModalOpen = true;
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
        $this->resetValidation();
    }

    public function save(): void
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
            'notes'        => $this->description, // DB expects notes column
            'receipt_path' => $receiptPath,
            'status'       => 'pending',
        ]);

        // Notify all admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new ExpenseSubmittedNotification($expense));
        }

        $this->closeModal();
        session()->flash('success', 'تم تقديم طلب المصروف بنجاح وهو بانتظار موافقة الإدارة.');
    }

    public function approve(int $expenseId): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $expense = CaseExpense::findOrFail($expenseId);
        $expense->update(['status' => 'approved', 'approved_by' => auth()->id()]);

        session()->flash('success', 'تمت الموافقة على المصروف بنجاح.');
    }

    public function rejectExpense(int $expenseId): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $expense = CaseExpense::findOrFail($expenseId);
        $expense->update(['status' => 'rejected', 'approved_by' => auth()->id()]);

        session()->flash('success', 'تم رفض طلب المصروف.');
    }

    public function confirmDelete(int $id): void
    {
        $this->confirmingDeleteId = $id;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeleteId = null;
    }

    public function deleteExpense(): void
    {
        abort_unless($this->confirmingDeleteId, 404);

        $user    = auth()->user();
        $expense = CaseExpense::findOrFail($this->confirmingDeleteId);

        // Only the submitter (if pending) or admin can delete
        abort_unless(
            $user->isAdmin() || ($expense->user_id === $user->id && $expense->status === 'pending'),
            403
        );

        if ($expense->receipt_path && Storage::disk('public')->exists($expense->receipt_path)) {
            Storage::disk('public')->delete($expense->receipt_path);
        }

        $expense->delete();
        $this->confirmingDeleteId = null;
        session()->flash('success', 'تم حذف طلب المصروف.');
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
