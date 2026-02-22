<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiLocalization
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. First priority: Check the URL directly (?lang=en)
        // This is very useful for testing from the browser
        $locale = $request->query('lang');

        // 2. Second priority: If not specified in the URL, check the Header (for Apps and Frontend)
        if (!$locale) {
            $locale = $request->header('Accept-Language');
        }

        // 3. String cleanup (to take only the first two characters, e.g., 'ar' or 'en')
        if ($locale) {
            $locale = substr($locale, 0, 2);
        }

        // 4. Verify if the locale is supported and activate it
        $supportedLocales = array_keys(config('language.supported', []));

        if ($locale && in_array($locale, $supportedLocales)) {
            app()->setLocale($locale);
        } else {
            // Default locale
            app()->setLocale('en');
        }

        return $next($request);
    }
}
