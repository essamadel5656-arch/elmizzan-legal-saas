<?php

namespace App\Livewire\Courts;

use Livewire\Component;
use App\Models\Court;
use App\Models\Jurisdiction;
use Illuminate\Support\Facades\Gate;

class CourtCreate extends Component
{
    public string $name = '';
    public $jurisdiction_id = '';

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

    public function mount(): void
    {
        Gate::authorize('manage-courts');
    }

    public function save(): void
    {
        Gate::authorize('manage-courts');

        $this->validate();

        Court::create([
            'name'            => $this->name,
            'jurisdiction_id' => $this->jurisdiction_id,
        ]);

        session()->flash('success', 'تم إضافة المحكمة بنجاح!');
        $this->redirect(route('courts.index'), navigate: true);
    }

    public function render()
    {
        $jurisdictions = Jurisdiction::orderBy('name')->get();

        return view('livewire.courts.court-create', compact('jurisdictions'))
            ->title('إضافة محكمة جديدة | ' . firm_name());
    }
}
