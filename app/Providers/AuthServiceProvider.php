<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UsersPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        User::class => UsersPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Super-admin tem acesso total — passa em qualquer verificação de policy/gate.
        Gate::before(fn (User $user) => $user->isSuperAdmin() ? true : null);

        ResetPassword::createUrlUsing(fn (object $notifiable, string $token) => config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}");

        //
    }
}
