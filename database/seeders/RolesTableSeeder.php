<?php

namespace Database\Seeders;

use App\Constants\Roles;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $filePath = public_path() . Roles::ROLE_PATH;
        $roles = json_decode(file_get_contents($filePath), true);
        foreach ($roles as $key => $role) {
            $request = [
                'guard_name' => 'api',
                'name' => $role['name']
            ];
        
            Role::firstOrCreate($request, $request);
        }
    }
}
