<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\ClientPayment;

class InPersonPaymentScheduledNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly ClientPayment $payment) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $isTransfer = $this->payment->mode === 'transfer';
        $message = $isTransfer
            ? 'تم إرسال إيصال تحويل بنكي بمبلغ ' . number_format($this->payment->amount) . ' ج.م. بانتظار التحقق.'
            : 'دفعة نقدية بالمكتب مجدولة بتاريخ ' . ($this->payment->payment_date?->format('d/m/Y') ?? '') . ' — ' . number_format($this->payment->amount) . ' ج.م.';

        return [
            'type'         => $isTransfer ? 'payment_transfer_submitted' : 'payment_scheduled',
            'payment_id'   => $this->payment->id,
            'case_id'      => $this->payment->case_id,
            'case_number'  => $this->payment->case?->case_number ?? '',
            'amount'       => $this->payment->amount,
            'payment_date' => $this->payment->payment_date?->format('Y-m-d') ?? now()->format('Y-m-d'),
            'client_name'  => $this->payment->client?->name ?? '',
            'message'      => $message,
        ];
    }
}
