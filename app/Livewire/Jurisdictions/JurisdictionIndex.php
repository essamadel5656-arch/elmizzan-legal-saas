<?php

namespace App\Livewire\Jurisdictions;

use Livewire\Component;
use App\Models\Jurisdiction;
use Illuminate\Support\Facades\Gate;

class JurisdictionIndex extends Component
{
    public function delete(int $id): void
    {
        Gate::authorize('manage-courts');

        $jurisdiction = Jurisdiction::findOrFail($id);
        $jurisdiction->delete();

        session()->flash('success', 'تم حذف نوع القضاء بنجاح!');
    }

    public function render()
    {
        $jurisdictions = Jurisdiction::with('courts')->orderBy('name')->get();

        return view('livewire.jurisdictions.jurisdiction-index', [
            'jurisdictions' => $jurisdictions,
        ])->title('أنواع جهات التقاضي | ' . firm_name());
    }
}
