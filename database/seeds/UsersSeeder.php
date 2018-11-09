<?php

use Illuminate\Database\Seeder;

/**
 * @package Resume Builder
 * @author  Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 10)->create()->each(function ($user) {
            $user->resumes()->saveMany(factory(App\Resume::class, 5)->make());
        });
    }
}
