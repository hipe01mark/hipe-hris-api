<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AssignPermissionToRole extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $approvers = [
            'super-admin',
            'admin',
            'manager',
        ];

        $roles = Role::whereIn('name', $approvers)->get();
        $permission = Permission::where('name', 'approver')->first();

        $permission->syncRoles($roles);
    }
}
