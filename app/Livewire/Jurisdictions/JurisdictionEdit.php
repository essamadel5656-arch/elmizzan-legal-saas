<?php

namespace App\Livewire\Jurisdictions;

use Livewire\Component;
use App\Models\Jurisdiction;
use Illuminate\Support\Facades\Gate;

class JurisdictionEdit extends Component
{
    public Jurisdiction $jurisdiction;
    public string $name = '';

    public function mount(Jurisdiction $jurisdiction): void
    {
        Gate::authorize('manage-courts');

        $this->jurisdiction = $jurisdiction;
        $this->name = (string) $jurisdiction->name;
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'اسم جهة التقاضي مطلوب.',
        ];
    }

    public function save(): void
    {
        Gate::authorize('manage-courts');

        $this->validate();

        $this->jurisdiction->update([
            'name' => $this->name,
        ]);

        session()->flash('success', 'تم تعديل نوع القضاء بنجاح!');
        $this->redirect(route('jurisdictions.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.jurisdictions.jurisdiction-edit')
            ->title('تعديل جهة التقاضي - ' . $this->jurisdiction->name . ' | ' . firm_name());
    }
}
