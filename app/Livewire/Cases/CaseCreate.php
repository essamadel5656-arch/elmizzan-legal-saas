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

    public int|string $client_id = '';
    public string $case_number = '';
    public int|string $lawyer_id = '';
    public int|string $jurisdiction_id = '';
    public int|string $court_level_id = '';
    public int|string $court_id = '';
    public string $circuit = '';
    public string $status = 'مفتوحة';
    public string $description = '';
    public ?float $costs = null;
    public ?float $total_costs = null;
    public ?float $deposit = null;
    public ?float $agreed_legal_fee = null;
    public string $Previous_procedure = '';
    public string $final_decision = '';
    public string $notes = '';
    public string $procuration = '';
    public string $rival_name = '';
    public string $rival_number = '';
    public string $rival_address = '';
    public string $rival_nid = '';
    public $case_file = null;

    // Client search properties
    public string $clientSearch = '';
    public ?array $selectedClient = null;

    public function getComputedRemainingProperty(): float
    {
        return ($this->total_costs ?? 0) - ($this->deposit ?? 0);
    }

    protected function rules(): array
    {
        return [
            'client_id'          => 'required|exists:clients,id',
            'case_number'        => 'required|string|max:100',
            'lawyer_id'          => auth()->user()?->role === 'lawyer'
                                        ? 'nullable'
                                        : 'required|exists:lawyers,id',
            'jurisdiction_id'    => 'required|exists:jurisdictions,id',
            'court_level_id'     => 'required|exists:court_levels,id',
            'court_id'           => 'nullable|exists:courts,id',
            'circuit'            => 'nullable|string|max:255',
            'status'             => 'required|string|in:مفتوحة,متداولة,مؤجلة,محجوزة للحكم,منتهية,مستأنفة,محفوظة,معلقة',
            'description'        => 'required|string|min:10',
            'costs'              => 'nullable|numeric|min:0',
            'total_costs'        => 'nullable|numeric|min:0',
            'deposit'            => 'nullable|numeric|min:0',
            'agreed_legal_fee'   => 'nullable|numeric|min:0',
            'Previous_procedure' => 'nullable|string|max:1000',
            'final_decision'     => 'nullable|string|max:2000',
            'notes'              => 'nullable|string|max:2000',
            'procuration'        => 'nullable|string|max:500',
            'rival_name'         => 'required|string|max:255',
            'rival_number'       => 'required|string|max:20',
            'rival_address'      => 'required|string|max:500',
            'rival_nid'          => 'required|string|max:14',
            'case_file'          => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:2048',
        ];
    }

    public function mount(): void
    {
        $this->authorize('create', LegalCase::class);

        if (auth()->user()?->role === 'lawyer') {
            $this->lawyer_id = (int) auth()->user()->lawyer_id;
        }
    }

    public function searchClients(): array
    {
        if (strlen($this->clientSearch) < 2) {
            return [];
        }

        return Client::where('name', 'like', '%' . $this->clientSearch . '%')
            ->orWhere('phone', 'like', '%' . $this->clientSearch . '%')
            ->select('id', 'name', 'phone', 'nid', 'address')
            ->limit(8)
            ->get()
            ->toArray();
    }

    public function selectClient(int $id, string $name, string $phone, string $nid, string $address): void
    {
        $this->client_id = $id;
        $this->selectedClient = [
            'id' => $id,
            'name' => $name,
            'phone' => $phone,
            'nid' => $nid,
            'address' => $address,
        ];
        $this->clientSearch = '';
    }

    public function clearClient(): void
    {
        $this->client_id = '';
        $this->selectedClient = null;
        $this->clientSearch = '';
    }

    public function updatedJurisdictionId(): void
    {
        $this->court_id = '';
    }

    public function updatedAgreedLegalFee(): void
    {
        if (! auth()->user()?->isAdmin()) {
            abort(403, 'غير مصرح لك بتحديد الأتعاب المتفق عليها.');
        }
    }

    public function resetForm(): void
    {
        $this->reset();
        $this->status = 'مفتوحة';
        if (auth()->user()?->role === 'lawyer') {
            $this->lawyer_id = (int) auth()->user()->lawyer_id;
        }
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

        $assignedLawyerId = auth()->user()?->role === 'lawyer'
            ? auth()->user()->lawyer_id
            : $this->lawyer_id;

        $case = LegalCase::create([
            'case_number'        => $this->case_number,
            'status'             => $this->status,
            'description'        => $this->description,
            'costs'              => auth()->user()?->isAdmin() ? ($this->costs ?: 0) : 0,
            'total_costs'        => auth()->user()?->isAdmin() ? ($this->total_costs ?: 0) : 0,
            'deposit'            => auth()->user()?->isAdmin() ? ($this->deposit ?: 0) : 0,
            'agreed_legal_fee'   => auth()->user()?->isAdmin() ? ($this->agreed_legal_fee ?: null) : null,
            'Previous_procedure' => $this->Previous_procedure ?: null,
            'final_decision'     => $this->final_decision ?: null,
            'notes'              => $this->notes ?: null,
            'procuration'        => $this->procuration ?: null,
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

        session()->flash('success', __('تم إضافة القضية رقم :number بنجاح', ['number' => $case->case_number]));
        $this->redirect(route('cases.index'), navigate: true);
    }

    public function render()
    {
        $courts = Court::when($this->jurisdiction_id, fn($q) =>
            $q->where('jurisdiction_id', $this->jurisdiction_id)
        )->select('id','name','jurisdiction_id')->orderBy('name')->get();

        return view('livewire.cases.case-create', [
            'lawyers'       => Lawyer::select('id','name')->orderBy('name')->get(),
            'jurisdictions' => Jurisdiction::select('id','name')->orderBy('name')->get(),
            'court_levels'  => CourtLevel::select('id','name')->orderBy('name')->get(),
            'clients'       => Client::select('id','name','phone','nid','address')->orderBy('name')->get(),
            'courts'        => $courts,
        ]);
    }
}
