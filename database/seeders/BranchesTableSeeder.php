<?php

namespace Database\Seeders;

use App\Constants\Branches;
use App\Models\Branch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class BranchesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $filePath = public_path() . Branches::BRANCH_PATH;
        $branches = json_decode(file_get_contents($filePath), true);

        foreach ($branches as $key => $branch) {
            $request = [
                'name' => $branch['name']
            ];

            Branch::firstOrCreate($request, $request);
        }
    }
}
