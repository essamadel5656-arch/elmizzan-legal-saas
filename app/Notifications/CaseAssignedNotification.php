<?php

namespace App\Notifications;

use App\Models\LegalCase;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CaseAssignedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected LegalCase $case,
        protected string $role = 'assistant'
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
        $roleLabel = match ($this->role) {
            'lead'       => 'محامي رئيسي',
            'assistant'  => 'مساعد',
            'consultant' => 'مستشار',
            default      => $this->role,
        };

        return [
            'type'        => 'case_assigned',
            'case_id'     => $this->case->id,
            'case_number' => $this->case->case_number,
            'role'        => $this->role,
            'message'     => "تم تعيينك بصفة {$roleLabel} على القضية رقم {$this->case->case_number}",
        ];
    }
}
