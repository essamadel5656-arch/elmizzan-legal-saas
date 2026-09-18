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
            session()->put('app_locale', $locale);
            
            // Optionally tie demo_country with language choice
            if ($country) {
                session()->put('demo_country', $country);
            }
            
            // Force the session to save before the redirect halts execution
            session()->save();
            
            return $this->redirect(request()->header('Referer') ?? '/');
        }
    }

    public function render()
    {
        return view('livewire.settings.locale-switcher');
    }
}
