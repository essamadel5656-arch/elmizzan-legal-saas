<?php

namespace App\Livewire\Lawyers;

use Livewire\Component;
use App\Models\Lawyer;
use App\Models\User;

class LawyerShow extends Component
{
    public Lawyer $lawyer;

    public function mount(Lawyer $lawyer): void
    {
        $this->lawyer = $lawyer;
    }

    public function delete(): void
    {
        $this->authorize('delete', $this->lawyer);

        User::where('lawyer_id', $this->lawyer->id)->delete();
        $this->lawyer->delete();

        session()->flash('success', 'تم حذف المحامي بنجاح.');
        $this->redirect(route('lawyers.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.lawyers.lawyer-show', [
            'lawyer' => $this->lawyer,
        ])->title('ملف المحامي: ' . $this->lawyer->name . ' | ' . firm_name());
    }
}
