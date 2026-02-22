<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema; // Ensure this class is imported

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Allow Super Admin to bypass all permission checks
        // Gate::before(function ($user, $ability) {
        //     return $user->hasRole('Super Admin') ? true : null;
        // });

        // Share settings with all site views (Frontend and Dashboard)
        // Add this condition to check if tables exist before querying to avoid errors during migrations
        if (Schema::hasTable('settings') && Schema::hasTable('setting_translations')) {

            // Fetch the first record with its translations
            $setting = Setting::first();

            if ($setting) {
                // Share settings data globally with all views
                $site_settings = [
                    'site_name'        => $setting->site_name, // The package will fetch the current locale automatically
                    'site_description' => $setting->site_description,
                    'copyright'        => $setting->copyright,
                    'site_logo'        => $setting->site_logo,
                    'site_email'       => $setting->site_email,
                    'site_phone'       => $setting->site_phone,
                    'maintenance_mode' => $setting->maintenance_mode,
                ];

                View::share('site_settings', $site_settings);
            }
        }
    }
}
