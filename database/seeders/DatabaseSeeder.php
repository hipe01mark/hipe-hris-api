<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(AssignPermissionToRole::class);
        $this->call(DepartmentsTableSeeder::class);
        $this->call(PositionsTableSeeder::class);
        $this->call(PassportTableSeeder::class);
        $this->call(BranchesTableSeeder::class);
        $this->call(StatusesTableSeeder::class);
    }
}
