<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\ResumeCreated' => [
            'App\Listeners\StoreResumeOnCloud'
        ],

        'App\Events\ResumeUpdated' => [
            'App\Listeners\StoreResumeOnCloud'
        ],

        'App\Events\UserRegistered' => [
            'App\Listeners\AssignUserRole',
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
