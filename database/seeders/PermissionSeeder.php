<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'view users',
            'edit users',
            'delete users',
            'create users',
            'view leads',
            'edit leads',
            'delete leads',
            'create leads',
            'view roles',
            'edit roles',
            'delete roles',
            'create roles',
            'view permissions',
            'edit permissions',
            'delete permissions',
            'create permissions',
            'view partners',
            'edit partners',
            'delete partners',
            'create partners',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
