<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Must be set in register() so it is available when PasskeysServiceProvider::boot() registers routes.
        // Otherwise the "password.confirm" middleware would still be attached and cause
        // "Password confirmation required" on POST /user/passkeys.
        config(['passkeys.management_middleware' => []]);
        config(['passkeys.redirect' => '/admin/dashboard']);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        // Fallback: strip "password.confirm" from passkey management routes if the
        // config override in register() was too late (provider boot order). This
        // guarantees "Add passkey" works inside the Livewire security panel without
        // requiring a recent password confirmation.
        $this->app->booted(function (): void {
            if (app()->routesAreCached()) {
                return;
            }

            $router = app('router');

            foreach (['passkey.registration-options', 'passkey.store', 'passkey.destroy'] as $name) {
                $route = $router->getRoutes()->getByName($name);

                if (! $route) {
                    continue;
                }

                $actionMiddleware = (array) ($route->getAction('middleware') ?? []);
                $filtered = array_values(array_filter(
                    $actionMiddleware,
                    fn ($m) => $m !== 'password.confirm' && $m !== 'password.confirm:web' && $m !== 'password.confirm:web,web'
                ));

                // Only update if something was removed
                if (count($filtered) !== count($actionMiddleware)) {
                    $route->setAction(array_merge($route->getAction(), ['middleware' => $filtered]));
                }
            }
        });
    }
}
