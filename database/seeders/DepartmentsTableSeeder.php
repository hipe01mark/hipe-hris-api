<?php

namespace Database\Seeders;

use App\Constants\Departments;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $filePath = public_path() . Departments::DEPARTMENT_PATH;
        $departments = json_decode(file_get_contents($filePath), true);

        foreach ($departments as $key => $department) {
            $request = [
                'name' => $department['name'],
                'hex_code' => $department['hex_code']
            ];

            Department::firstOrCreate($request, $request);
        }
    }
}
