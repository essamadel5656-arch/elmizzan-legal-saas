<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Show the settings form (admin only — enforced by route middleware).
     */
    public function edit()
    {
        $appName = Setting::get('app_name', 'الميزان');
        return view('settings.edit', compact('appName'));
    }

    /**
     * Persist the settings and clear cache.
     */
    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:100',
        ], [
            'app_name.required' => 'اسم المكتب مطلوب.',
            'app_name.max'      => 'اسم المكتب لا يتجاوز 100 حرف.',
        ]);

        Setting::set('app_name', trim($request->app_name));

        return redirect()->route('settings.edit')
            ->with('success', 'تم حفظ الإعدادات بنجاح');
    }
}
