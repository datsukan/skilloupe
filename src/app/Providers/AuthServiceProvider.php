<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // 管理権限
        Gate::define('manage', function ($user) {
            return (bool) $user->is_manage;
        });

        // 更新権限
        Gate::define('update', function ($user, $id) {
            return (bool) $user->is_manage || $user->id === $id;
        });
    }
}
