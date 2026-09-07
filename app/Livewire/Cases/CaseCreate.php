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
use App\Mail\NewCaseMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CaseCreate extends Component
{
    use WithFileUploads;

    public $client_id = '';
    public string $case_number = '';
    public $lawyer_id = '';
    public $jurisdiction_id = '';
    public $court_level_id = '';
    public $court_id = '';
    public string $circuit = '';
    public string $status = 'مفتوحة';
    public string $description = '';
    public $costs = null;
    public $total_costs = null;
    public $deposit = null;
    public $agreed_legal_fee = null;

    public function updatedAgreedLegalFee(): void
    {
        if (! auth()->user()?->isAdmin()) {
            abort(403, 'غير مصرح لك بتحديد الأتعاب المتفق عليها.');
        }
    }
    public string $Previous_procedure = '';
    public string $final_decision = '';
    public string $notes = '';
    public string $procuration = '';
    public string $rival_name = '';
    public string $rival_number = '';
    public string $rival_address = '';
    public string $rival_nid = '';
    public $case_file = null;

    protected function rules(): array
    {
        return [
            'client_id'          => 'required|exists:clients,id',
            'case_number'        => 'required|string',
            'lawyer_id'          => auth()->user()?->role === 'lawyer' ? 'nullable' : 'required|exists:lawyers,id',
            'jurisdiction_id'    => 'required|exists:jurisdictions,id',
            'court_level_id'     => 'required|exists:court_levels,id',
            'court_id'           => 'nullable|exists:courts,id',
            'circuit'            => 'nullable|string|max:255',
            'status'             => 'required|string',
            'description'        => 'nullable|string',
            'costs'              => 'nullable|numeric',
            'total_costs'        => 'nullable|numeric',
            'deposit'            => 'nullable|numeric',
            'Previous_procedure' => 'nullable|string',
            'final_decision'     => 'nullable|string',
            'notes'              => 'nullable|string',
            'rival_name'         => 'required|string',
            'rival_number'       => 'required|string',
            'rival_address'      => 'required|string',
            'rival_nid'          => 'required|string',
            'case_file'          => 'nullable|file|mimes:pdf,doc,docx,png,jpg|max:2048',
        ];
    }

    protected function messages(): array
    {
        return [
            'client_id.required'          => 'برجاء اختيار العميل صاحب القضية.',
            'client_id.exists'            => 'العميل المختار غير مسجل في النظام.',
            'case_number.required'        => 'برجاء إدخال رقم القضية.',
            'lawyer_id.required'          => 'برجاء اختيار المحامي المسؤول عن القضية.',
            'lawyer_id.exists'            => 'المحامي المختار غير مسجل بالنظام.',
            'jurisdiction_id.required'    => 'جهة التقاضي مطلوبة.',
            'court_level_id.required'     => 'درجة التقاضي مطلوبة.',
            'status.required'             => 'حالة القضية مطلوبة.',
            'rival_name.required'         => 'اسم الخصم مطلوب.',
            'rival_number.required'       => 'رقم الخصم مطلوب.',
            'rival_address.required'      => 'عنوان الخصم مطلوب.',
            'rival_nid.required'          => 'الرقم القومي للخصم مطلوب.',
            'case_file.file'              => 'الملف المرفوع يجب أن يكون ملفاً صحيحاً.',
            'case_file.mimes'             => 'صيغ الملفات المسموحة هي: pdf, doc, docx, png, jpg.',
            'case_file.max'               => 'حجم الملف لا يجب أن يتخطى 2 ميجابايت.',
        ];
    }

    public function mount(): void
    {
        $this->authorize('create', LegalCase::class);

        if (auth()->user()?->role === 'lawyer') {
            $this->lawyer_id = auth()->user()->lawyer_id;
        }
    }

    public function updatedJurisdictionId(): void
    {
        $this->court_id = '';
    }

    public function save(): void
    {
        $this->authorize('create', LegalCase::class);

        if ($this->agreed_legal_fee !== null && ! auth()->user()?->isAdmin()) {
            abort(403, 'غير مصرح لك بتحديد الأتعاب المتفق عليها.');
        }

        $this->validate();

        $filePath = null;
        if ($this->case_file) {
            $filePath = $this->case_file->store('cases_attachments', 'public');
        }

        $assignedLawyerId = auth()->user()->role === 'lawyer'
            ? auth()->user()->lawyer_id
            : $this->lawyer_id;

        $case = LegalCase::create([
            'case_number'        => $this->case_number,
            'status'             => $this->status,
            'description'        => $this->description ?: null,
            'costs'              => auth()->user()?->isAdmin() ? ($this->costs ?: 0) : 0,
            'total_costs'        => auth()->user()?->isAdmin() ? ($this->total_costs ?: 0) : 0,
            'deposit'            => auth()->user()?->isAdmin() ? ($this->deposit ?: 0) : 0,
            'agreed_legal_fee'   => auth()->user()?->isAdmin() ? ($this->agreed_legal_fee ?: null) : null,
            'Previous_procedure' => $this->Previous_procedure ?: 'لا يوجد إشعار سابق',
            'final_decision'     => $this->final_decision ?: 'لم يصدر حكم بعد',
            'notes'              => $this->notes ?: 'لا توجد ملاحظات',
            'procuration'        => $this->procuration ?: 'لا يوجد',
            'rival_name'         => $this->rival_name,
            'rival_number'       => $this->rival_number,
            'rival_address'      => $this->rival_address,
            'rival_nid'          => $this->rival_nid,
            'court_id'           => $this->court_id ?: null,
            'jurisdiction_id'    => $this->jurisdiction_id,
            'court_level_id'     => $this->court_level_id,
            'circuit'            => $this->circuit ?: null,
            'lawyer_id'          => $assignedLawyerId,
            'case_file'          => $filePath,
        ]);

        DB::table('client_case')->insert([
            'case_id'    => $case->id,
            'client_id'  => $this->client_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Attach lead lawyer in lawyerscases pivot
        if ($assignedLawyerId) {
            $case->lawyers()->syncWithoutDetaching([
                $assignedLawyerId => ['role' => 'lead']
            ]);

            $lawyer = Lawyer::find($assignedLawyerId);
            if ($lawyer && $lawyer->email) {
                try {
                    Mail::to($lawyer->email)->send(new NewCaseMail($case));
                } catch (\Throwable $e) {
                    // Fail silently on mail issues
                }
            }
        }

        session()->flash('success', 'تم إضافة القضية رقم ' . $case->case_number . ' بنجاح');
        $this->redirect(route('cases.index'), navigate: true);
    }

    public function render()
    {
        $lawyers       = Lawyer::select('id', 'name')->orderBy('name')->get();
        $jurisdictions = Jurisdiction::select('id', 'name')->orderBy('name')->get();
        $court_levels  = CourtLevel::select('id', 'name')->orderBy('name')->get();
        $clients       = Client::select('id', 'name', 'phone', 'nid', 'address')->orderBy('name')->get();

        $courts = Court::when($this->jurisdiction_id, function ($q) {
            $q->where('jurisdiction_id', $this->jurisdiction_id);
        })->select('id', 'name', 'jurisdiction_id')->orderBy('name')->get();

        return view('livewire.cases.case-create', compact(
            'lawyers', 'jurisdictions', 'courts', 'court_levels', 'clients'
        ))->title('إضافة قضية جديدة | ' . firm_name());
    }
}
