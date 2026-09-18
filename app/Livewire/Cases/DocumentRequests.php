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
    public bool   $isModalOpen = false;
    public ?int   $confirmingDeleteId = null;
    public string $document_name = '';
    public string $notes = '';
    public ?int   $client_id   = null;
    public $file = null;

    // Media Viewer State
    public bool $isMediaViewerOpen = false;
    public array $mediaGallery = [];
    public int $currentMediaIndex = 0;

    protected function rules(): array
    {
        return [
            'document_name' => 'required|string|max:255',
            'notes'         => 'nullable|string|max:2000',
            'client_id'     => 'required|exists:clients,id',
            'file'          => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:5120',
        ];
    }

    protected function messages(): array
    {
        return [
            'document_name.required' => 'اسم المستند مطلوب.',
            'client_id.required'     => 'يجب تحديد الموكل.',
            'file.mimes'             => 'صيغ الملف المقبولة: pdf, doc, docx, png, jpg, jpeg.',
            'file.max'               => 'حجم الملف لا يتجاوز 5 ميجابايت.',
        ];
    }

    public function mount(LegalCase $case): void
    {
        $this->case = $case;
        // Default to first client on the case
        $this->client_id = $case->clients->first()?->id;
    }

    public function openModal(): void
    {
        $this->reset(['document_name', 'notes', 'file']);
        $this->isModalOpen = true;
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
        $this->resetValidation();
    }

    public function confirmDelete(int $id): void
    {
        $this->confirmingDeleteId = $id;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeleteId = null;
    }

    public function save(): void
    {
        $user = auth()->user();
        abort_unless($user->isAdmin() || $user->isLawyer(), 403);

        $this->validate();

        $filePath = null;
        $fileName = null;
        if ($this->file) {
            $filePath = $this->file->store('case_documents', 'public');
            $fileName = $this->file->getClientOriginalName();
        }

        DocumentRequest::create([
            'case_id'      => $this->case->id,
            'client_id'    => $this->client_id,
            'requested_by' => $user->id,
            'title'        => $this->document_name, // Map to DB column
            'description'  => $this->notes ?: null, // Map to DB column
            'status'       => $filePath ? 'uploaded' : 'pending',
            'file_path'    => $filePath,
            'file_name'    => $fileName,
            'uploaded_at'  => $filePath ? now() : null,
        ]);

        $this->closeModal();
        session()->flash('success', 'تم حفظ المستند بنجاح.');
    }

    public function approveDocument(int $requestId): void
    {
        abort_unless(auth()->user()->isAdmin() || auth()->user()->isLawyer(), 403);

        $docRequest = DocumentRequest::findOrFail($requestId);
        $docRequest->update(['status' => 'approved']);

        session()->flash('success', 'تم قبول المستند وتصنيفه بنجاح.');
    }

    public function deleteDocument(): void
    {
        abort_unless($this->confirmingDeleteId, 404);
        abort_unless(auth()->user()?->isAdmin(), 403);

        $docRequest = DocumentRequest::findOrFail($this->confirmingDeleteId);
        if ($docRequest->file_path && Storage::disk('public')->exists($docRequest->file_path)) {
            Storage::disk('public')->delete($docRequest->file_path);
        }
        $docRequest->delete();

        $this->confirmingDeleteId = null;
        session()->flash('success', __('تم حذف المستند بنجاح.'));
        $this->loadMediaGallery();
    }

    public function loadMediaGallery(): void
    {
        $this->mediaGallery = [];

        if ($this->case->case_file && Storage::disk('public')->exists($this->case->case_file)) {
            $this->mediaGallery[] = [
                'id' => 'main_file',
                'url' => asset('storage/' . $this->case->case_file),
                'path' => $this->case->case_file,
                'type' => $this->getFileType($this->case->case_file),
                'title' => __('ملف القضية الرئيسي'),
                'is_main_file' => true,
            ];
        }

        $documents = DocumentRequest::where('case_id', $this->case->id)->get();
        foreach ($documents as $doc) {
            if ($doc->file_path && Storage::disk('public')->exists($doc->file_path)) {
                $this->mediaGallery[] = [
                    'id' => $doc->id,
                    'url' => asset('storage/' . $doc->file_path),
                    'path' => $doc->file_path,
                    'type' => $this->getFileType($doc->file_path),
                    'title' => $doc->title ?? __('مستند إضافي'),
                    'is_main_file' => false,
                ];
            }
        }
    }

    private function getFileType($path): string
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'])) {
            return 'image';
        }
        if ($ext === 'pdf') {
            return 'pdf';
        }
        if (in_array($ext, ['doc', 'docx'])) {
            return 'word';
        }
        return 'other';
    }

    public function openMediaViewerForDocument($docId): void
    {
        $this->loadMediaGallery();
        if (count($this->mediaGallery) > 0) {
            $index = collect($this->mediaGallery)->search(fn($media) => $media['id'] == $docId);
            $this->currentMediaIndex = $index !== false ? $index : 0;
            $this->isMediaViewerOpen = true;
        }
    }

    public function nextMedia(): void
    {
        if ($this->currentMediaIndex < count($this->mediaGallery) - 1) {
            $this->currentMediaIndex++;
        }
    }

    public function prevMedia(): void
    {
        if ($this->currentMediaIndex > 0) {
            $this->currentMediaIndex--;
        }
    }

    public function closeMediaViewer(): void
    {
        $this->isMediaViewerOpen = false;
    }

    public function deleteCurrentMedia(): void
    {
        if (!auth()->user()?->isAdmin()) {
            abort(403, __('غير مصرح لك بحذف المرفقات.'));
        }

        if (!isset($this->mediaGallery[$this->currentMediaIndex])) {
            return;
        }

        $currentMedia = $this->mediaGallery[$this->currentMediaIndex];

        if ($currentMedia['is_main_file']) {
            session()->flash('error', __('لا يمكن حذف الملف الرئيسي للقضية من هنا. استخدم صفحة تعديل القضية.'));
            return;
        } else {
            $docRequest = DocumentRequest::find($currentMedia['id']);
            if ($docRequest) {
                if ($docRequest->file_path && Storage::disk('public')->exists($docRequest->file_path)) {
                    Storage::disk('public')->delete($docRequest->file_path);
                }
                $docRequest->delete();
            }
        }
        
        $this->loadMediaGallery();

        if (count($this->mediaGallery) === 0) {
            $this->isMediaViewerOpen = false;
        } else {
            if ($this->currentMediaIndex >= count($this->mediaGallery)) {
                $this->currentMediaIndex = count($this->mediaGallery) - 1;
            }
        }
        session()->flash('success', __('تم حذف المستند المرفق بنجاح.'));
    }

    public function render()
    {
        $documents = DocumentRequest::where('case_id', $this->case->id)
            ->with(['client', 'requestedBy'])
            ->latest()
            ->get();

        $clients = $this->case->clients;

        return view('livewire.cases.document-requests', compact('documents', 'clients'));
    }
}
