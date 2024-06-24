<?php

namespace Database\Seeders;

use App\Constants\Permissions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $filePath = public_path() . Permissions::PERMISSION_PATH;
        $permissions = json_decode(file_get_contents($filePath), true);

        foreach ($permissions as $key => $permission) {
            $request = [
                'guard_name' => 'api',
                'name' => $permission['name']
            ];

            Permission::firstOrCreate($request, $request);
        }
    }
}
