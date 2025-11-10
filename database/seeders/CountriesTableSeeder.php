<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('countries')->insert([
            [
                'country_code' => 'JM',
                'name' => 'Jamaica',
                'flag_emoji' => '🇯🇲',
                'is_active' => 1,
                'launch_date' => '2025-10-26',
                'total_adm1' => 14,
                'total_adm2' => 826,
                'map_center_lat' => 18.10960000,
                'map_center_lng' => -77.29750000,
                'map_zoom_level' => 9,
                'primary_language' => 'en',
                'secondary_language' => null,
                'timezone' => 'America/Jamaica',
                'disaster_mode_active' => 0,
            ]
        ]);
    }
}
