<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * @package Resume Builder
 * @author  Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // create permissions
        $permission_create_resumes = Permission::create(['name' => 'create resumes']);
        $permission_delete_resumes = Permission::create(['name' => 'delete resumes']);
        $permission_edit_resumes   = Permission::create(['name' => 'edit resumes']);

        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'delete users']);
        $permission_edit_users = Permission::create(['name' => 'edit users']);

        // create roles and assign created permissions
        $role1 = Role::create(['name' => 'administrator']);
        $role1->givePermissionTo(Permission::all());

        $role2 = Role::create(['name' => 'moderator']);
        $role2->givePermissionTo([
            $permission_create_resumes,
            $permission_delete_resumes,
            $permission_edit_resumes,
            $permission_edit_users
        ]);

        $role3 = Role::create(['name' => 'subscriber']);
        $role3->givePermissionTo([
            $permission_create_resumes,
            $permission_delete_resumes,
            $permission_edit_resumes
        ]);
    }
}
