<?php

namespace Database\Seeders;

use App\Constants\Statuses;
use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $filePath = public_path() . Statuses::STATUS_PATH;
        $statuses = json_decode(file_get_contents($filePath), true);
        foreach ($statuses as $key => $status) {
            Status::firstorcreate([
                'name' => $status['name'] 
            ]);
        }
    }
}
