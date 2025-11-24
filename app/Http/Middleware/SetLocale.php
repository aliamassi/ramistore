<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get locale from URL parameter, default to 'en'
        $locale = $request->query('lang', config('app.locale', 'en'));
        
        // List of supported locales
        $supportedLocales = ['en', 'ar'];
        
        // Validate and set locale
        if (in_array($locale, $supportedLocales)) {
            app()->setLocale($locale);
        }
        
        return $next($request);
    }
}
