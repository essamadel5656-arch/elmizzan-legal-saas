<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Illuminate\Support\Facades\Session;

class LocaleSwitcher extends Component
{
    public function setLocale($locale, $country = null)
    {
        $supported = ['ar', 'en'];
        if (in_array($locale, $supported, true)) {
            Session::put('app_locale', $locale);
            
            // Optionally tie demo_country with language choice
            if ($country) {
                Session::put('demo_country', $country);
            }
            
            $this->redirect(request()->header('Referer') ?? '/');
        }
    }

    public function render()
    {
        return view('livewire.settings.locale-switcher');
    }
}
