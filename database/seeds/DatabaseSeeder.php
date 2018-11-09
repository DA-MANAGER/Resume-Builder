<?php

use Illuminate\Database\Seeder;

/**
 * @package Resume Builder
 * @author  Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(OccupationResponsibilitySeeder::class);
        $this->call(OptionsSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);
    }
}
