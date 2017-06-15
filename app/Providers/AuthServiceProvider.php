<?php

namespace App\Providers;

use App\Cooperation;
use App\Partner;
use App\Policies\CooperationPolicy;
use App\Policies\PartnerPolicy;
use App\Policies\UserPolicy;
use App\User;
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

        //
    }
}
