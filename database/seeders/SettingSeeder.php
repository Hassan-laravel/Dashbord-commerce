<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the main record (Static data)
        $setting = Setting::create([
            'site_email' => 'admin@store.com',
            'site_phone' => '00966500000000',
            'site_logo'  => null, // You can set a default image path here
            'maintenance_mode' => false,
        ]);

        // Add translations for each supported language in the project
        // Use a loop to ensure coverage of all languages in config/language.supported
        foreach (config('language.supported') as $code => $lang) {
            if ($code == 'ar') {
                $setting->translateOrNew($code)->fill([
                    'site_name' => 'متجرنا الإلكتروني',
                    'site_description' => 'أفضل المنتجات بأقل الأسعار',
                    'copyright' => 'جميع الحقوق محفوظة © ' . date('Y'),
                ]);
            } else {
                // Default data for English or other languages
                $setting->translateOrNew($code)->fill([
                    'site_name' => 'Our E-Store',
                    'site_description' => 'Best products with best prices',
                    'copyright' => 'All rights reserved © ' . date('Y'),
                ]);
            }
        }

        // Save translations to the database
        $setting->save();

        $this->command->info('Settings Seeded Successfully!');
    }
}
