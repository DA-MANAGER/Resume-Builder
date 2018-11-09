<?php

namespace App\Providers;

use App\Store\LaravelRave as Rave;
use KingFlamez\Rave\RaveServiceProvider as ServiceProvider;
use Unirest\Request;
use Unirest\Request\Body;

/**
 * @package Resume Builder
 * @author  Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
class LaravelRaveServiceProvider extends ServiceProvider
{
    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('laravelrave', function ($app) {
            return new Rave($app->make("request"), new Request, new Body);
        });

        $this->app->alias('laravelrave', 'App\Store\LaravelRave');
    }
}
