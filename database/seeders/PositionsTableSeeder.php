<?php

namespace Database\Seeders;

use App\Constants\Positions;
use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class PositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $filePath = public_path() . Positions::POSITION_PATH;
        $positions = json_decode(file_get_contents($filePath), true);

        foreach ($positions as $key => $position) {
            $request = [
                'name' => $position['name']
            ];

            Position::firstOrCreate($request, $request);
        }
    }
}
