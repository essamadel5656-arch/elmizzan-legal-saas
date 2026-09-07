<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\CaseExpense;

class ExpenseSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly CaseExpense $expense) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'        => 'expense_submitted',
            'expense_id'  => $this->expense->id,
            'case_id'     => $this->expense->case_id,
            'case_number' => $this->expense->case?->case_number ?? '',
            'amount'      => $this->expense->amount,
            'category'    => $this->expense->category,
            'submitted_by'=> $this->expense->submittedBy?->name ?? '',
            'message'     => 'طلب مصروف جديد بانتظار الموافقة: ' . number_format($this->expense->amount) . ' — ' . $this->expense->category,
        ];
    }
}
