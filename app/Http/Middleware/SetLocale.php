<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;


class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // 1. Manejar el Idioma
        $language = setting('app_language', config('app.locale'));
        App::setLocale($language);

        // 2. Manejar la Zona Horaria
        // Buscamos el setting 'app_timezone', si no existe usamos el de config/app.php
        $timezone = setting('app_timezone', config('app.timezone'));

        // Esto cambia la configuración para Laravel y para PHP globalmente
        Config::set('app.timezone', $timezone);
        date_default_timezone_set($timezone);

        return $next($request);
    }
}
