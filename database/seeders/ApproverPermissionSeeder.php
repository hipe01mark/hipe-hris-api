<?php

namespace Database\Seeders;

use App\Constants\Roles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ApproverPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Model::unguard();

        $approvers = [
            Roles::SUPER_ADMIN,
            Roles::ADMIN,
            Roles::MANAGER,
            Roles::TEAM_LEAD
        ];

        $roles = Role::whereIn('id', $approvers)->get();
        $permission = Permission::where('name', 'approver')->first();

        $permission->syncRoles($roles);
    }
}
