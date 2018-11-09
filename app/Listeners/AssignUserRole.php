<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\Permission\Models\Role;

/**
 * @package Resume Builder
 * @author  Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
class AssignUserRole
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * 
     * @return void
     */
    public function handle($event)
    {
        $user = $event->user;

        // We'll assign the very basic role to the newly registered user
        // if they're not assigned a role already.
        if (! $user->hasAnyRole(Role::all())) {
            $user->assignRole('subscriber');
        }
    }
}
