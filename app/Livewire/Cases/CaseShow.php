<?php

namespace App\Livewire\Cases;

use Livewire\Component;
use App\Models\LegalCase;

class CaseShow extends Component
{
    public LegalCase $case;

    public function mount(LegalCase $case): void
    {
        $this->case = $case->load([
            'clients',
            'court.jurisdiction',
            'courtLevel',
            'lawyers',
            'appointments',
            'documents',
        ]);

        $this->authorize('view', $this->case);
    }

    public function updateStatus(string $newStatus): void
    {
        $this->authorize('update', $this->case);

        $allowed = ['مفتوحة', 'متداولة', 'مؤجلة', 'محجوزة للحكم', 'منتهية', 'مستأنفة', 'محفوظة', 'معلقة'];
        if (in_array($newStatus, $allowed)) {
            $this->case->status = $newStatus;
            $this->case->save();
            session()->flash('success', 'تم تحديث حالة القضية إلى: ' . $newStatus);
        }
    }

    public function render()
    {
        return view('livewire.cases.case-show', [
            'case' => $this->case,
        ])->title('تفاصيل القضية - ' . $this->case->case_number . ' | ' . firm_name());
    }
}
