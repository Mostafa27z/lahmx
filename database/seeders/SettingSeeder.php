<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'company_name' => 'لحمكس',
            'company_phone' => '050-123-4567',
            'company_whatsapp' => '050-123-4567',
            'company_email' => 'info@lahmix.com',
            'company_address' => 'الرياض، المملكة العربية السعودية',
            'social_snapchat' => 'https://snapchat.com',
            'social_tiktok' => 'https://tiktok.com',
            'social_facebook' => 'https://facebook.com',
            'social_instagram' => 'https://instagram.com',
            'stat_satisfied_clients' => '+500',
            'stat_experience_years' => '+10',
            'stat_halal_certified' => '100%',
            'stat_daily_slaughter' => 'يومي',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }
    }
}
