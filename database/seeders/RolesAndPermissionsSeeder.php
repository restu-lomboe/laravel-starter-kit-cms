<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Create default permissions for each module and a Super Admin role
     * holding every permission.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $modules = ['permission', 'roles', 'users'];

        $levelDetails = [
            'index' => ['read', 'Can view the list of :module'],
            'create' => ['create', 'Can create a new :singular'],
            'update' => ['update', 'Can update :module records'],
            'detail' => ['read', 'Can view :module details'],
            'delete' => ['delete', 'Can delete :module records'],
        ];

        foreach ($modules as $module) {
            foreach ($levelDetails as $level => [$value, $template]) {
                Permission::updateOrCreate(
                    ['name' => "{$module}.{$level}", 'guard_name' => 'web'],
                    [
                        'description' => str($template)->replace(':module', $module)->replace(':singular', str($module)->singular()),
                        'page' => $module,
                        'level' => $value,
                    ]
                );
            }
        }

        $superAdmin = Role::findOrCreate('Super Admin', 'web');
        $superAdmin->syncPermissions(Permission::all());
    }
}
