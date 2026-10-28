<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
 use Illuminate\Support\Str;

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
        // Limitar los registros de ususarios por direción de IP
        RateLimiter::for('registration', function(Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        // Limitar las solicitudes de recuperación de contraseñas
        RateLimiter::for('password-reset', function(Request $request) {
            $email = Str::lower($request->input('email', ''));

            return [
                Limit::perMinute(10)->by($request->ip()),

                Limit::perMinute(3)->by($email),
            ];
        });

        // Limitar envios del formulario de contacto
        RateLimiter::for('contact', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        // Limitar reservas de turno por usuario
        RateLimiter::for('appointment-booking', function (Request $request) {
            return Limit::perMinute(5)->by($request->user()->id);
        });

        // Limitar  consultas de horarios por usuario
        RateLimiter::for('appointment-slots', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()->id);
        });

        // Limittar cancelaciones de turnos por usuario
        RateLimiter::for('appointment-cancel', function (Request $request)  {
            return Limit::perMinute(5)->by($request->user()->id);
        });
    }
}
