<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = config('constants.permissions');

        foreach($permissions as $permission){
            Permission::create(['name' => $permission]);
        }

        $roles = config('constants.roles');
        foreach($roles as $role){
            $role = Role::create(['name' => $role['name']]);
            $role->givePermissionTo($role['permissions']);
        }
        // this can be done as separate statements
//        $role = Role::create(['name' => 'filler']);
//        $role->givePermissionTo(['access-machines']);


//        // or may be done by chaining
//        $role = Role::create(['name' => 'moderator'])
//            ->givePermissionTo(['publish articles', 'unpublish articles']);

//        $role = Role::create(['name' => 'super-admin']);
//        $role->givePermissionTo(Permission::all());
    }
}
