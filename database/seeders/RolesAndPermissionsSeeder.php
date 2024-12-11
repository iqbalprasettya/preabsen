<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Schema;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat roles
        $roles = [
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'employee' => 'Employee'
        ];

        foreach ($roles as $key => $role) {
            Role::create(['name' => $key]);
        }

        // Ambil semua permission yang ada di tabel permissions
        $permissions = Permission::all();

        // Assign permissions ke role
        $superAdmin = Role::findByName('super_admin');
        $admin = Role::findByName('admin');
        $employee = Role::findByName('employee');

        // Super Admin mendapat semua permissions
        $superAdmin->syncPermissions($permissions);

        // Admin mendapat semua permissions kecuali role management
        $adminPermissions = $permissions->filter(function ($permission) {
            return !str_contains($permission->name, 'role');
        });
        $admin->syncPermissions($adminPermissions);

        // Employee hanya mendapat permissions view dan create untuk attendance dan leave request
        $employeePermissions = $permissions->filter(function ($permission) {
            return (str_contains($permission->name, 'view_attendance') ||
                    str_contains($permission->name, 'create_attendance') ||
                    str_contains($permission->name, 'view_leave::request') ||
                    str_contains($permission->name, 'create_leave::request'));
        });
        $employee->syncPermissions($employeePermissions);
    }
} 