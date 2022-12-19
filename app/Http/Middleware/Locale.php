<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    //Change language
    public function handle(Request $request, Closure $next)
    {
        $raw_locale = Session::get('locale');
        if (in_array($raw_locale, Config::get('app.locales'))) {
            $locale = $raw_locale;
        } else $locale = Config::get('app.locale');
        App::setLocale($locale);
        return $next($request);
    }
}
