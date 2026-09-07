<?php

namespace App\Livewire\ClientPortal;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\DocumentRequest;
use App\Models\LegalCase;
use App\Models\Lawyer;
use App\Models\User;
use App\Notifications\DocumentUploadedNotification;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DocumentUpload extends Component
{
    use WithFileUploads;

    public DocumentRequest $documentRequest;
    public $file = null;

    protected function rules(): array
    {
        return [
            'file' => 'required|file|mimes:pdf,docx,doc,xlsx,xls,png,jpg,jpeg,webp|max:10240',
        ];
    }

    protected function messages(): array
    {
        return [
            'file.required' => 'يرجى اختيار ملف للرفع.',
            'file.mimes'    => 'الصيغ المقبولة: PDF, DOCX, XLSX, PNG, JPG, WebP.',
            'file.max'      => 'الحجم الأقصى للملف 10 ميجابايت.',
        ];
    }

    public function mount(DocumentRequest $documentRequest): void
    {
        // Ensure only the correct client can upload
        $user = auth()->user();
        abort_unless($user->isClient() && $user->client_id === $documentRequest->client_id, 403);

        $this->documentRequest = $documentRequest;
    }

    public function upload(): void
    {
        $this->validate();

        $path     = $this->file->store('document_requests', 'public');
        $fileName = $this->file->getClientOriginalName();

        $this->documentRequest->update([
            'status'      => 'uploaded',
            'file_path'   => $path,
            'file_name'   => $fileName,
            'uploaded_at' => Carbon::now(),
        ]);

        // Notify lead lawyer
        $this->notifyLeadLawyer();

        $this->reset('file');
        session()->flash('upload_success', 'تم رفع المستند بنجاح. سيتم مراجعته قريباً.');
        $this->redirect(route('client-portal.dashboard'), navigate: true);
    }

    private function notifyLeadLawyer(): void
    {
        $case = LegalCase::with('lawyers')->find($this->documentRequest->case_id);
        if (!$case) return;

        // Find lead lawyer's user account
        $leadLawyer = $case->lawyers->firstWhere('pivot.role', 'lead');
        if ($leadLawyer) {
            $lawyerUser = User::where('lawyer_id', $leadLawyer->id)->first();
            $lawyerUser?->notify(new DocumentUploadedNotification($this->documentRequest));
        }

        // Also notify admins
        User::where('role', 'admin')->each(function ($admin) {
            $admin->notify(new DocumentUploadedNotification($this->documentRequest));
        });
    }

    public function render()
    {
        return view('livewire.client-portal.document-upload')
            ->title('رفع مستند | ' . firm_name());
    }
}
