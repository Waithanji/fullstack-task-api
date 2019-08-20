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
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // $this->app['auth']->viaRequest('api', function ($request) {
        //     $api_token = empty($request->header('x-api-key')) ? $request->json('x_api_key') : $request->header('x-api-key');
        //     return User::where('api_token', $api_token)->first();

        // });
    }
}
