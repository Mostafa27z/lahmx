<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Show the form for editing the settings.
     */
    public function edit()
    {
        $settings = [
            'company_name' => Setting::get('company_name', 'لحمكس'),
            'company_phone' => Setting::get('company_phone', '050-123-4567'),
            'company_whatsapp' => Setting::get('company_whatsapp', '050-123-4567'),
            'company_email' => Setting::get('company_email', 'info@lahmix.com'),
            'company_address' => Setting::get('company_address', 'الرياض، المملكة العربية السعودية'),
            'social_snapchat' => Setting::get('social_snapchat', 'https://snapchat.com'),
            'social_tiktok' => Setting::get('social_tiktok', 'https://tiktok.com'),
            'social_facebook' => Setting::get('social_facebook', 'https://facebook.com'),
            'social_instagram' => Setting::get('social_instagram', 'https://instagram.com'),
            'stat_satisfied_clients' => Setting::get('stat_satisfied_clients', '+500'),
            'stat_experience_years' => Setting::get('stat_experience_years', '+10'),
            'stat_halal_certified' => Setting::get('stat_halal_certified', '100%'),
            'stat_daily_slaughter' => Setting::get('stat_daily_slaughter', 'يومي'),
        ];

        return view('admin.settings.edit', compact('settings'));
    }

    /**
     * Update the settings in storage.
     */
    public function update(Request $request)
    {
        $keys = [
            'company_name',
            'company_phone',
            'company_whatsapp',
            'company_email',
            'company_address',
            'social_snapchat',
            'social_tiktok',
            'social_facebook',
            'social_instagram',
            'stat_satisfied_clients',
            'stat_experience_years',
            'stat_halal_certified',
            'stat_daily_slaughter',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key));
            }
        }

        return redirect()->route('admin.settings.edit')->with('success', 'تم تحديث معلومات وإعدادات الشركة بنجاح.');
    }
}
