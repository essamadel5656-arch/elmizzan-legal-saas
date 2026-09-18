<?php

namespace App\Livewire\Cases;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\LegalCase;
use App\Models\Client;
use App\Models\Court;
use App\Models\CourtLevel;
use App\Models\Jurisdiction;
use App\Models\Lawyer;
use App\Models\User;
use App\Notifications\CaseAssignedNotification;
use Illuminate\Support\Facades\Storage;

class CaseEdit extends Component
{
    use WithFileUploads;

    public LegalCase $case;

    public string $case_number = '';
    public string $status = 'مفتوحة';
    public int|string $court_id = '';
    public int|string $jurisdiction_id = '';
    public string $judicial_authority_type = '';
    public int|string $court_level_id = '';
    public string $circuit = '';
    public string $description = '';
    public string $Previous_procedure = '';
    public string $final_decision = '';
    public string $notes = '';

    public string $rival_name = '';
    public string $rival_number = '';
    public string $rival_nid = '';
    public string $rival_address = '';

    public $costs = null;
    public $total_costs = null;
    public $deposit = null;
    public $agreed_legal_fee = null;

    public array $client_ids = [];
    public array $lawyer_ids = [];
    public array $lawyer_roles = [];

    public $case_file = null;

    public bool $isMediaViewerOpen = false;
    public array $mediaGallery = [];
    public int $currentMediaIndex = 0;

    protected function rules(): array
    {
        return [
            'client_ids'              => 'required|array|min:1',
            'client_ids.*'            => 'exists:clients,id',
            'lawyer_ids'              => 'required|array|min:1',
            'lawyer_ids.*'            => 'exists:lawyers,id',
            'lawyer_roles'            => 'nullable|array',
            'case_number'             => 'required|string',
            'jurisdiction_id'         => 'required|exists:jurisdictions,id',
            'court_level_id'          => 'required|exists:court_levels,id',
            'circuit'                 => 'nullable|string|max:255',
            'status'                  => 'required|string',
            'court_id'                => 'nullable|exists:courts,id',
            'judicial_authority_type' => 'nullable|string',
            'description'             => 'required|string',
            'costs'                   => 'nullable|numeric',
            'total_costs'             => 'nullable|numeric',
            'deposit'                 => 'nullable|numeric',
            'Previous_procedure'      => 'nullable|string',
            'final_decision'          => 'nullable|string',
            'notes'                   => 'nullable|string',
            'rival_name'              => 'required|string',
            'rival_number'            => 'required|string',
            'rival_address'           => 'required|string',
            'rival_nid'               => 'required|string',
            'case_file'               => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:2048',
        ];
    }

    protected function messages(): array
    {
        return [
            'client_ids.required'      => 'برجاء اختيار عميل واحد على الأقل صاحب القضية.',
            'client_ids.min'           => 'برجاء اختيار عميل واحد على الأقل صاحب القضية.',
            'lawyer_ids.required'      => 'برجاء اختيار محامي واحد على الأقل مسؤول عن القضية.',
            'lawyer_ids.min'           => 'برجاء اختيار محامي واحد على الأقل مسؤول عن القضية.',
            'case_number.required'     => 'برجاء إدخال رقم القضية.',
            'jurisdiction_id.required' => 'جهة التقاضي مطلوبة.',
            'court_level_id.required'  => 'درجة التقاضي مطلوبة.',
            'status.required'          => 'حالة القضية مطلوبة.',
            'rival_name.required'      => 'اسم الخصم مطلوب.',
            'rival_number.required'    => 'رقم الخصم مطلوب.',
            'rival_address.required'   => 'عنوان الخصم مطلوب.',
            'rival_nid.required'       => 'الرقم القومي للخصم مطلوب.',
            'description.required'     => 'ملخص وقائع القضية مطلوب.',
            'case_file.file'           => 'الملف المرفوع يجب أن يكون ملفاً صحيحاً.',
            'case_file.mimes'          => 'صيغ الملفات المسموحة هي: pdf, doc, docx, png, jpg, jpeg.',
            'case_file.max'            => 'حجم الملف لا يجب أن يتخطى 2 ميجابايت.',
        ];
    }

    public function mount(LegalCase $case): void
    {
        $this->authorize('update', $case);

        $this->case = $case->load(['clients', 'lawyers', 'court.jurisdiction', 'documents']);

        $this->case_number             = (string) $case->case_number;
        $this->status                  = (string) $case->status;
        $this->court_id                = $case->court_id ?? '';
        $this->jurisdiction_id         = $case->jurisdiction_id ?? ($case->court?->jurisdiction_id ?? '');
        $this->judicial_authority_type = (string) ($case->judicial_authority_type ?? '');
        $this->court_level_id          = $case->court_level_id ?? '';
        $this->circuit                 = (string) ($case->circuit ?? '');
        $this->description             = (string) ($case->description ?? '');
        $this->Previous_procedure      = (string) ($case->Previous_procedure ?? '');
        $this->final_decision          = (string) ($case->final_decision ?? '');
        $this->notes                   = (string) ($case->notes ?? '');

        $this->rival_name              = (string) ($case->rival_name ?? '');
        $this->rival_number            = (string) ($case->rival_number ?? '');
        $this->rival_nid               = (string) ($case->rival_nid ?? '');
        $this->rival_address           = (string) ($case->rival_address ?? '');

        $this->costs                   = $case->costs;
        $this->total_costs             = $case->total_costs;
        $this->deposit                 = $case->deposit;
        $this->agreed_legal_fee        = $case->agreed_legal_fee;

        $this->client_ids              = $case->clients->pluck('id')->map(fn($id) => (int)$id)->toArray();
        $this->lawyer_ids              = $case->lawyers->pluck('id')->map(fn($id) => (int)$id)->toArray();

        $this->lawyer_roles = [];
        foreach ($case->lawyers as $lawyer) {
            $this->lawyer_roles[$lawyer->id] = $lawyer->pivot->role ?? 'assistant';
        }

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

        if ($this->case->relationLoaded('documents')) {
            foreach ($this->case->documents as $doc) {
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

    public function openMediaViewer($index = 0): void
    {
        $this->loadMediaGallery();
        if (count($this->mediaGallery) > 0) {
            $this->currentMediaIndex = max(0, min($index, count($this->mediaGallery) - 1));
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
            if (Storage::disk('public')->exists($this->case->case_file)) {
                Storage::disk('public')->delete($this->case->case_file);
            }
            $this->case->update(['case_file' => null]);
            $this->case_file = null;
        } else {
            $doc = \App\Models\DocumentRequest::find($currentMedia['id']);
            if ($doc) {
                if (Storage::disk('public')->exists($doc->file_path)) {
                    Storage::disk('public')->delete($doc->file_path);
                }
                $doc->delete();
            }
        }
        
        $this->case->load('documents');
        $this->loadMediaGallery();

        if (count($this->mediaGallery) === 0) {
            $this->isMediaViewerOpen = false;
        } else {
            if ($this->currentMediaIndex >= count($this->mediaGallery)) {
                $this->currentMediaIndex = count($this->mediaGallery) - 1;
            }
        }
    }

    public function updatedJurisdictionId(): void
    {
        $this->court_id = '';
        if ($this->jurisdiction_id) {
            $jurisdiction = Jurisdiction::find($this->jurisdiction_id);
            if ($jurisdiction) {
                $this->judicial_authority_type = $jurisdiction->name;
            }
        }
    }

    public function save(): void
    {
        $this->authorize('update', $this->case);

        if ($this->agreed_legal_fee !== null && (float)$this->agreed_legal_fee !== (float)$this->case->agreed_legal_fee && ! auth()->user()?->isAdmin()) {
            abort(403, __('غير مصرح لك بتحديد أو تعديل الأتعاب المتفق عليها.'));
        }

        $this->validate();

        $filePath = $this->case->case_file;
        if ($this->case_file) {
            if ($this->case->case_file && Storage::disk('public')->exists($this->case->case_file)) {
                Storage::disk('public')->delete($this->case->case_file);
            }
            $filePath = $this->case_file->store('cases_attachments', 'public');
        }

        $updateData = [
            'case_number'             => $this->case_number,
            'status'                  => $this->status,
            'description'             => $this->description ?: null,
            'Previous_procedure'      => $this->Previous_procedure ?: null,
            'final_decision'          => $this->final_decision ?: null,
            'court_level_id'          => $this->court_level_id,
            'notes'                   => $this->notes ?: null,
            'rival_name'              => $this->rival_name,
            'rival_number'            => $this->rival_number,
            'rival_address'           => $this->rival_address,
            'rival_nid'               => $this->rival_nid,
            'jurisdiction_id'         => $this->jurisdiction_id,
            'court_id'                => $this->court_id ?: null,
            'circuit'                 => $this->circuit ?: null,
            'judicial_authority_type' => $this->judicial_authority_type ?: null,
            'case_file'               => $filePath,
        ];

        if (auth()->user()?->isAdmin()) {
            $updateData['costs']            = $this->costs ?? 0;
            $updateData['total_costs']      = $this->total_costs ?? 0;
            $updateData['deposit']          = $this->deposit ?? 0;
            $updateData['agreed_legal_fee'] = $this->agreed_legal_fee ?: null;
        }

        $this->case->update($updateData);

        $this->case->clients()->sync($this->client_ids);

        $lawyersSyncData = [];
        $roleMapping = [
            'محامي رئيسي' => 'lead',
            'مساعد'       => 'assistant',
            'مستشار'      => 'consultant',
            'lead'        => 'lead',
            'assistant'   => 'assistant',
            'consultant'  => 'consultant',
        ];

        foreach ($this->lawyer_ids as $lawyerId) {
            $incomingRole = $this->lawyer_roles[$lawyerId] ?? 'assistant';
            $dbRole = $roleMapping[$incomingRole] ?? 'assistant';
            $lawyersSyncData[$lawyerId] = ['role' => $dbRole];
        }

        $this->case->lawyers()->sync($lawyersSyncData);

        session()->flash('success', __('تم تحديث القضية بنجاح'));
        $this->redirect(route('cases.show', $this->case->id), navigate: true);
    }

    public function render()
    {
        $courts = Court::when($this->jurisdiction_id, function ($q) {
            $q->where('jurisdiction_id', $this->jurisdiction_id);
        })->get();

        $lawyers = Lawyer::orderBy('name')->get();
        $jurisdictions = Jurisdiction::orderBy('name')->get();
        $court_levels = CourtLevel::orderBy('name')->get();

        if (auth()->user()->role === 'lawyer') {
            $lawyerCaseIds = LegalCase::where('lawyer_id', auth()->user()->lawyer_id)->pluck('id');
            $clients = Client::whereHas('cases', function ($query) use ($lawyerCaseIds) {
                $query->whereIn('cases.id', $lawyerCaseIds);
            })->select('id', 'name', 'phone', 'nid', 'address')->orderBy('name')->get();
        } else {
            $clients = Client::select('id', 'name', 'phone', 'nid', 'address')->orderBy('name')->get();
        }

        return view('livewire.cases.case-edit', compact(
            'courts', 'lawyers', 'jurisdictions', 'court_levels', 'clients'
        ))->title(__('تعديل القضية') . ' - ' . $this->case->case_number . ' | ' . firm_name());
    }
}
