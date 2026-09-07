<?php

namespace App\Livewire\Courts;

use Livewire\Component;
use App\Models\Court;
use Illuminate\Support\Facades\Gate;

class CourtIndex extends Component
{
    public string $search = '';

    public function delete(int $id): void
    {
        Gate::authorize('manage-courts');

        $court = Court::findOrFail($id);
        $court->delete();

        session()->flash('success', 'تم حذف المحكمة بنجاح!');
    }

    public function render()
    {
        $courts = Court::with(['jurisdiction'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('jurisdiction', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy('name')
            ->get();

        return view('livewire.courts.court-index', [
            'courts' => $courts,
        ])->title('إدارة المحاكم | ' . firm_name());
    }
}
