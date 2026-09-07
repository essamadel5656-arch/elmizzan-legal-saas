<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Gate;
use App\Models\Setting;
use App\Models\Lawyer;
use App\Models\Client;
use App\Models\LegalCase;
use App\Models\User;
use App\Policies\LawyerPolicy;
use App\Policies\ClientPolicy;
use App\Policies\LegalCasePolicy;
use App\Policies\SettingPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Policy map: Model → Policy class.
     * Laravel 11 auto-discovers policies in app/Policies via convention,
     * but explicit registration here serves as authoritative documentation.
     */
    protected array $policies = [
        Lawyer::class    => LawyerPolicy::class,
        Client::class    => ClientPolicy::class,
        LegalCase::class => LegalCasePolicy::class,
    ];

    public function boot(): void
    {
        // ── Register model policies ──────────────────────────────────────────
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }

        // ── Coarse-grained admin Gates (for controllers without model instances) ──
        Gate::define('manage-lawyers',   fn(User $u) => $u->role === 'admin');
        Gate::define('manage-settings',  fn(User $u) => $u->role === 'admin');
        Gate::define('manage-users',     fn(User $u) => $u->role === 'admin');
        Gate::define('manage-courts',    fn(User $u) => $u->role === 'admin');

        // ── Share global view variables to every authenticated view ──────────
        View::composer('*', function ($view) {
            // Dynamic firm name sourced from settings table (cached)
            $view->with('appName', firm_name());

            if (auth()->check()) {
                // Unread DB notifications count for the bell badge
                $view->with('notificationsCount', auth()->user()->unreadNotifications()->count());
            } else {
                $view->with('notificationsCount', 0);
            }
        });
    }
}