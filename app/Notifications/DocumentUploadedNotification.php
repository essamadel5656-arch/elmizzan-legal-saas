<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\DocumentRequest;

class DocumentUploadedNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly DocumentRequest $documentRequest) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'            => 'document_uploaded',
            'document_request_id' => $this->documentRequest->id,
            'case_id'         => $this->documentRequest->case_id,
            'case_number'     => $this->documentRequest->case?->case_number ?? '',
            'title'           => $this->documentRequest->title,
            'client_name'     => $this->documentRequest->client?->name ?? '',
            'message'         => 'الموكل رفع مستنداً جديداً: "' . $this->documentRequest->title . '" — يتطلب مراجعتك.',
        ];
    }
}
