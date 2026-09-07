<?php

namespace App\Livewire\Lawyers;

use Livewire\Component;
use App\Models\Lawyer;
use App\Models\User;

class LawyerIndex extends Component
{
    public string $search = '';
    public string $degree = '';

    public function clearFilters(): void
    {
        $this->search = '';
        $this->degree = '';
    }

    public function delete(int $id): void
    {
        $lawyer = Lawyer::findOrFail($id);
        $this->authorize('delete', $lawyer);

        User::where('lawyer_id', $lawyer->id)->delete();
        $lawyer->delete();

        session()->flash('success', 'تم حذف المحامي بنجاح.');
    }

    public function render()
    {
        $lawyers = Lawyer::when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('id', $this->search)
                      ->orWhere('license_number', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->degree, function ($query) {
                $query->where('degree', $this->degree);
            })
            ->orderBy('id', 'desc')
            ->get();

        return view('livewire.lawyers.lawyer-index', [
            'lawyers' => $lawyers,
        ])->title('قائمة المحامين | ' . firm_name());
    }
}
