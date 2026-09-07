<?php

namespace App\Livewire\Courts;

use Livewire\Component;
use App\Models\Court;
use App\Models\Jurisdiction;
use Illuminate\Support\Facades\Gate;

class CourtEdit extends Component
{
    public Court $court;
    public string $name = '';
    public $jurisdiction_id = '';

    public function mount(Court $court): void
    {
        Gate::authorize('manage-courts');

        $this->court = $court;
        $this->name = (string) $court->name;
        $this->jurisdiction_id = (string) $court->jurisdiction_id;
    }

    protected function rules(): array
    {
        return [
            'name'            => 'required|string|max:255',
            'jurisdiction_id' => 'required|exists:jurisdictions,id',
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required'            => 'اسم المحكمة مطلوب.',
            'jurisdiction_id.required' => 'جهة التقاضي مطلوبة.',
        ];
    }

    public function save(): void
    {
        Gate::authorize('manage-courts');

        $this->validate();

        $this->court->update([
            'name'            => $this->name,
            'jurisdiction_id' => $this->jurisdiction_id,
        ]);

        session()->flash('success', 'تم تعديل المحكمة بنجاح!');
        $this->redirect(route('courts.index'), navigate: true);
    }

    public function render()
    {
        $jurisdictions = Jurisdiction::orderBy('name')->get();

        return view('livewire.courts.court-edit', compact('jurisdictions'))
            ->title('تعديل المحكمة - ' . $this->court->name . ' | ' . firm_name());
    }
}
