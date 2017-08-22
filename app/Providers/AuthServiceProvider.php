<?php

namespace App\Providers;

use App\Cooperation;
use App\Partner;
use App\Policies\CooperationPolicy;
use App\Policies\PartnerPolicy;
use App\Policies\UserPolicy;
use App\User;
use App\UserAuth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider {
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model'        => 'App\Policies\ModelPolicy',
        Cooperation::class => CooperationPolicy::class,
        Partner::class     => PartnerPolicy::class,
        User::class        => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin-menu', function ($user)
        {
            return (
                UserAuth::where('username', $user->username)
                    ->where('auth_type', 'SU')
                    ->exists()
                ||
                UserAuth::where('username', $user->username)
                    ->where('auth_type', 'SAU')
                    ->exists()
                ||
                UserAuth::where('username', $user->username)
                    ->where('auth_type', 'AU')
                    ->exists()
            );
//            return (Auths::where('user_id', $user->id)->where('auth_object_ref_id', '1')->exists() ||
//                Auths::where('user_id', $user->id)->where('auth_object_ref_id', '2')->exists());
        });
    }
}
