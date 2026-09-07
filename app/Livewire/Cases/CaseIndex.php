<?php

namespace App\Livewire\Cases;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LegalCase;
use App\Models\Jurisdiction;
use App\Models\Court;
use Illuminate\Support\Facades\Storage;

class CaseIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $jurisdictionFilter = '';
    public string $courtFilter = '';
    public string $lawyerRoleFilter = ''; // lead, assistant, consultant
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';

    public ?int $confirmingDeleteId = null;

    protected $queryString = [
        'search'             => ['except' => ''],
        'statusFilter'       => ['except' => ''],
        'jurisdictionFilter' => ['except' => ''],
        'courtFilter'        => ['except' => ''],
        'lawyerRoleFilter'   => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingJurisdictionFilter(): void
    {
        $this->courtFilter = '';
        $this->resetPage();
    }

    public function updatingCourtFilter(): void
    {
        $this->resetPage();
    }

    public function updatingLawyerRoleFilter(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->jurisdictionFilter = '';
        $this->courtFilter = '';
        $this->lawyerRoleFilter = '';
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->confirmingDeleteId = $id;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeleteId = null;
    }

    public function deleteCase(int $id): void
    {
        $case = LegalCase::findOrFail($id);
        $this->authorize('delete', $case);

        if ($case->case_file && Storage::disk('public')->exists($case->case_file)) {
            Storage::disk('public')->delete($case->case_file);
        }

        $caseNumber = $case->case_number;
        $case->delete();

        $this->confirmingDeleteId = null;
        session()->flash('success', 'تم حذف القضية رقم ' . $caseNumber . ' بنجاح.');
    }

    public function render()
    {
        $user = auth()->user();

        $query = LegalCase::with(['court', 'jurisdiction', 'courtLevel', 'clients', 'lawyers'])
            // Scoped for lawyers
            ->when($user && $user->role === 'lawyer', function ($q) use ($user) {
                $lawyerId = $user->lawyer_id;
                if ($this->lawyerRoleFilter === 'lead') {
                    $q->where('cases.lawyer_id', $lawyerId);
                } elseif ($this->lawyerRoleFilter === 'assistant') {
                    $q->whereHas('lawyers', function ($sub) use ($lawyerId) {
                        $sub->where('lawyerscases.lawyer_id', $lawyerId)
                            ->where('lawyerscases.role', 'assistant');
                    });
                } elseif ($this->lawyerRoleFilter === 'consultant') {
                    $q->whereHas('lawyers', function ($sub) use ($lawyerId) {
                        $sub->where('lawyerscases.lawyer_id', $lawyerId)
                            ->where('lawyerscases.role', 'consultant');
                    });
                } else {
                    $q->where(function ($subQ) use ($lawyerId) {
                        $subQ->where('cases.lawyer_id', $lawyerId)
                             ->orWhereHas('lawyers', function ($pivotQ) use ($lawyerId) {
                                 $pivotQ->where('lawyerscases.lawyer_id', $lawyerId);
                             });
                    });
                }
            })
            // Search
            ->when($this->search, function ($q) {
                $term = trim($this->search);
                $q->where(function ($sub) use ($term) {
                    $sub->where('case_number', 'like', "%{$term}%")
                        ->orWhere('rival_name', 'like', "%{$term}%")
                        ->orWhere('circuit', 'like', "%{$term}%")
                        ->orWhereHas('clients', function ($clientQ) use ($term) {
                            $clientQ->where('name', 'like', "%{$term}%");
                        });
                });
            })
            // Status filter
            ->when($this->statusFilter, function ($q) {
                $q->where('status', $this->statusFilter);
            })
            // Jurisdiction filter
            ->when($this->jurisdictionFilter, function ($q) {
                $q->where('jurisdiction_id', $this->jurisdictionFilter);
            })
            // Court filter
            ->when($this->courtFilter, function ($q) {
                $q->where('court_id', $this->courtFilter);
            })
            ->orderBy($this->sortBy, $this->sortDirection);

        $cases = $query->paginate(12);

        $jurisdictions = Jurisdiction::select('id', 'name')->orderBy('name')->get();
        $courts = Court::when($this->jurisdictionFilter, function ($q) {
            $q->where('jurisdiction_id', $this->jurisdictionFilter);
        })->select('id', 'name', 'jurisdiction_id')->orderBy('name')->get();

        return view('livewire.cases.case-index', [
            'cases'         => $cases,
            'jurisdictions' => $jurisdictions,
            'courts'        => $courts,
        ])->title('إدارة القضايا | ' . firm_name());
    }
}
