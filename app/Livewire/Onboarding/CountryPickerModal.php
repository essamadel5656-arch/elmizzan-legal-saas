<?php

namespace App\Livewire\Onboarding;

use Livewire\Component;
use App\Models\Setting;

class CountryPickerModal extends Component
{
    public bool $show = true;
    public string $otherCountry = '';

    public function selectCountry(string $code): void
    {
        $valid = ['EG', 'SA', 'AE', 'OM', 'LY', 'IQ', 'KW', 'BH', 'QA', 'YE', 'MA', 'JO', 'LB', 'SY'];
        abort_unless(in_array($code, $valid), 422);

        Setting::set('firm_country', $code);
        $this->show = false;
        $this->dispatch('country-selected', country: $code);
        $this->redirect('/home', navigate: true);
    }

    public function selectOther(): void
    {
        if (!empty($this->otherCountry)) {
            $this->selectCountry($this->otherCountry);
        }
    }

    public function render()
    {
        return view('livewire.onboarding.country-picker-modal');
    }
}
