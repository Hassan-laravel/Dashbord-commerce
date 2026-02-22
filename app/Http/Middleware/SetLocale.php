<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if a locale is stored in the session
        if (Session::has('locale')) {
            $locale = Session::get('locale');
        } else {
            // If not found, use the default locale from the configuration file
            $locale = config('language.default');
        }

        // Verify that the locale is supported in our configuration file
        if (!array_key_exists($locale, config('language.supported'))) {
            $locale = config('language.default');
        }

        // Set the current application locale
        App::setLocale($locale);

        return $next($request);
    }
}
