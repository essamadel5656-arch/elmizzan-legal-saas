<?php

namespace App\Livewire\Cases;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\LegalCase;
use App\Models\DocumentRequest;
use App\Models\User;
use App\Notifications\DocumentUploadedNotification;
use Illuminate\Support\Facades\Storage;

class DocumentRequests extends Component
{
    use WithFileUploads;

    public LegalCase $case;

    // Form state
    public bool   $showForm    = false;
    public string $title       = '';
    public string $description = '';
    public ?int   $client_id   = null;

    protected function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'client_id'   => 'required|exists:clients,id',
        ];
    }

    protected function messages(): array
    {
        return [
            'title.required'     => 'عنوان المستند المطلوب مطلوب.',
            'client_id.required' => 'يجب تحديد الموكل.',
        ];
    }

    public function mount(LegalCase $case): void
    {
        $this->case = $case;
        // Default to first client on the case
        $this->client_id = $case->clients->first()?->id;
    }

    public function createRequest(): void
    {
        $user = auth()->user();
        abort_unless($user->isAdmin() || $user->isLawyer(), 403);

        $this->validate();

        DocumentRequest::create([
            'case_id'      => $this->case->id,
            'client_id'    => $this->client_id,
            'requested_by' => $user->id,
            'title'        => $this->title,
            'description'  => $this->description ?: null,
            'status'       => 'pending',
        ]);

        $this->reset(['title', 'description', 'showForm']);
        session()->flash('doc_success', 'تم إرسال طلب المستند إلى الموكل بنجاح.');
    }

    public function approveDocument(int $requestId): void
    {
        abort_unless(auth()->user()->isAdmin() || auth()->user()->isLawyer(), 403);

        $docRequest = DocumentRequest::findOrFail($requestId);
        $docRequest->update(['status' => 'approved']);

        session()->flash('doc_success', 'تم قبول المستند وتصنيفه بنجاح.');
    }

    public function deleteRequest(int $requestId): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $docRequest = DocumentRequest::findOrFail($requestId);
        if ($docRequest->file_path && Storage::disk('public')->exists($docRequest->file_path)) {
            Storage::disk('public')->delete($docRequest->file_path);
        }
        $docRequest->delete();

        session()->flash('doc_success', 'تم حذف طلب المستند.');
    }

    public function render()
    {
        $requests = DocumentRequest::where('case_id', $this->case->id)
            ->with(['client', 'requestedBy'])
            ->latest()
            ->get();

        $clients = $this->case->clients;

        return view('livewire.cases.document-requests', compact('requests', 'clients'));
    }
}
