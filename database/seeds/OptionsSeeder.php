<?php

use App\Option;
use Illuminate\Database\Seeder;

/**
 * @package Resume Builder
 * @author  Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
class OptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Here we will store some default options that can be accessed by
        // the application whenever required.
        $templates = ['caride'];

        Option::create([
            'name'  => 'ignore_templates',
            'value' => serialize($templates)
        ]);
    }
}
