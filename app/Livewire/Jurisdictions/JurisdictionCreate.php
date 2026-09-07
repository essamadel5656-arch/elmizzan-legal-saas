<?php

namespace App\Livewire\Jurisdictions;

use Livewire\Component;
use App\Models\Jurisdiction;
use Illuminate\Support\Facades\Gate;

class JurisdictionCreate extends Component
{
    public string $name = '';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:jurisdictions,name',
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'اسم جهة التقاضي مطلوب.',
            'name.unique'   => 'اسم جهة التقاضي مسجل بالفعل.',
        ];
    }

    public function mount(): void
    {
        Gate::authorize('manage-courts');
    }

    public function save(): void
    {
        Gate::authorize('manage-courts');

        $this->validate();

        Jurisdiction::create([
            'name' => $this->name,
        ]);

        session()->flash('success', 'تم إضافة نوع القضاء بنجاح!');
        $this->redirect(route('jurisdictions.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.jurisdictions.jurisdiction-create')
            ->title('إضافة جهة تقاضي جديدة | ' . firm_name());
    }
}
