<?php

namespace Database\Seeders;

use App\Models\ParkingSpot;
use Illuminate\Database\Seeder;

class ParkingSpotSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 60; $i++) {
            ParkingSpot::create();
        }
    }
}
