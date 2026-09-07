<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Setting;
use Illuminate\Support\Facades\Gate;

class SettingsEdit extends Component
{
    // General
    public string $app_name = '';
    public string $firm_country = 'EG';

    // PWA Settings
    public string $pwa_short_name       = '';
    public string $pwa_theme_color      = '';
    public string $pwa_background_color = '';
    public string $pwa_description      = '';
    
    // Theme Customization
    public string $primary_color        = '';

    // Payment settings
    public string $bank_name        = '';
    public string $bank_iban        = '';
    public string $instapay_address = '';
    public string $mobile_wallet    = '';

    // AI feature
    public bool $ai_feature_enabled = false;

    public bool $saved = false;

    protected function rules(): array
    {
        return [
            'app_name'             => 'required|string|min:2|max:100',
            'firm_country'         => 'required|string|max:4',
            'pwa_short_name'       => 'nullable|string|max:255',
            'pwa_theme_color'      => 'nullable|string|max:255',
            'pwa_background_color' => 'nullable|string|max:255',
            'pwa_description'      => 'nullable|string|max:500',
            'primary_color'        => 'nullable|string|max:7',
            'bank_name'            => 'nullable|string|max:100',
            'bank_iban'          => 'nullable|string|max:100',
            'instapay_address'   => 'nullable|string|max:100',
            'mobile_wallet'      => 'nullable|string|max:20',
            'ai_feature_enabled' => 'boolean',
        ];
    }

    protected function messages(): array
    {
        return [
            'app_name.required' => 'اسم المكتب مطلوب.',
            'app_name.min'      => 'اسم المكتب يجب ألا يقل عن حرفين.',
            'app_name.max'      => 'اسم المكتب يجب ألا يتجاوز 100 حرف.',
        ];
    }

    public function mount(): void
    {
        // 🔒 Only admin can access and modify settings
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'هذه الصفحة متاحة للمدير فقط.');
        }

        Gate::authorize('manage-settings');

        $this->app_name             = firm_name();
        $this->firm_country         = tenant_setting('firm_country', 'EG');
        $this->pwa_short_name       = Setting::get('pwa_short_name', $this->app_name);
        $this->pwa_theme_color      = Setting::get('pwa_theme_color', '#1e293b');
        $this->pwa_background_color = Setting::get('pwa_background_color', '#f8fafc');
        $this->pwa_description      = Setting::get('pwa_description', 'نظام إدارة القضايا القانونية المتكاملة');
        $this->primary_color        = Setting::get('primary_color', '#d4af37');
        $this->bank_name            = Setting::get('bank_name', '');
        $this->bank_iban            = Setting::get('bank_iban', '');
        $this->instapay_address   = Setting::get('instapay_address', '');
        $this->mobile_wallet      = Setting::get('mobile_wallet', '');
        $this->ai_feature_enabled = (bool)(int) Setting::get('ai_feature_enabled', '0');
    }

    public function save(): void
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'هذه الصفحة متاحة للمدير فقط.');
        }

        Gate::authorize('manage-settings');

        $this->validate();

        Setting::set('app_name',             $this->app_name);
        Setting::set('firm_country',         $this->firm_country);
        Setting::set('pwa_short_name',       $this->pwa_short_name);
        Setting::set('pwa_theme_color',      $this->pwa_theme_color);
        Setting::set('pwa_background_color', $this->pwa_background_color);
        Setting::set('pwa_description',      $this->pwa_description);
        Setting::set('primary_color',        $this->primary_color);
        Setting::set('bank_name',            $this->bank_name);
        Setting::set('bank_iban',            $this->bank_iban);
        Setting::set('instapay_address',   $this->instapay_address);
        Setting::set('mobile_wallet',      $this->mobile_wallet);
        Setting::set('ai_feature_enabled', $this->ai_feature_enabled ? '1' : '0');

        $this->saved = true;

        session()->flash('success', 'تم حفظ وتحديث إعدادات المكتب بنجاح.');
        $this->dispatch('firm-name-updated', name: $this->app_name);
    }

    public function render()
    {
        return view('livewire.settings.settings-edit')
            ->title('إعدادات المكتب | ' . $this->app_name);
    }
}
