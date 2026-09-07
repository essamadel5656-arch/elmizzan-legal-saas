<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CaseSessionUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Appointment $appointment,
        protected string $action = 'created'  // 'created' | 'updated'
    ) {}

    /**
     * Deliver via the database channel only.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Payload stored in the notifications table.
     */
    public function toArray(object $notifiable): array
    {
        $caseNumber = $this->appointment->case?->case_number ?? '—';
        $caseId     = $this->appointment->case?->id ?? null;
        $date       = \Carbon\Carbon::parse($this->appointment->date)->format('Y/m/d');
        $actionLabel = $this->action === 'created' ? 'تمت إضافة' : 'تم تحديث';

        return [
            'type'             => 'case_session_updated',
            'case_id'          => $caseId,
            'case_number'      => $caseNumber,
            'appointment_date' => $date,
            'action'           => $this->action,
            'message'          => "{$actionLabel} جلسة بتاريخ {$date} على القضية رقم {$caseNumber}",
        ];
    }
}
