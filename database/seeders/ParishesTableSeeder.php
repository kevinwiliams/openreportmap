<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParishesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('parishes')->insert([
            ['geonames_id' => 3488716, 'name' => 'Saint Andrew', 'ascii_name' => 'St Andrew', 'parish_code' => '08', 'latitude' => 18.06667000, 'longitude' => -76.75000000, 'population' => 555995, 'community_count' => 106, 'country_code' => 'JM'],
            ['geonames_id' => 3488711, 'name' => 'Saint Catherine', 'ascii_name' => 'St Catherine', 'parish_code' => '10', 'latitude' => 18.06667000, 'longitude' => -77.01667000, 'population' => 529218, 'community_count' => 51, 'country_code' => 'JM'],
            ['geonames_id' => 3490952, 'name' => 'Clarendon', 'ascii_name' => 'Clarendon', 'parish_code' => '01', 'latitude' => 17.98333000, 'longitude' => -77.30000000, 'population' => 243857, 'community_count' => 83, 'country_code' => 'JM'],
            ['geonames_id' => 3489586, 'name' => 'Manchester', 'ascii_name' => 'Manchester', 'parish_code' => '04', 'latitude' => 18.05000000, 'longitude' => -77.53333000, 'population' => 191796, 'community_count' => 74, 'country_code' => 'JM'],
            ['geonames_id' => 3488700, 'name' => 'Saint James', 'ascii_name' => 'St James', 'parish_code' => '12', 'latitude' => 18.38333000, 'longitude' => -77.88333000, 'population' => 180498, 'community_count' => 73, 'country_code' => 'JM'],
            ['geonames_id' => 3488715, 'name' => 'Saint Ann', 'ascii_name' => 'St Ann', 'parish_code' => '09', 'latitude' => 18.35000000, 'longitude' => -77.26667000, 'population' => 171739, 'community_count' => 54, 'country_code' => 'JM'],
            ['geonames_id' => 3488708, 'name' => 'Saint Elizabeth', 'ascii_name' => 'St Elizabeth', 'parish_code' => '11', 'latitude' => 18.05000000, 'longitude' => -77.78333000, 'population' => 145409, 'community_count' => 61, 'country_code' => 'JM'],
            ['geonames_id' => 3488081, 'name' => 'Westmoreland', 'ascii_name' => 'Westmoreland', 'parish_code' => '16', 'latitude' => 18.23333000, 'longitude' => -78.15000000, 'population' => 141393, 'community_count' => 82, 'country_code' => 'JM'],
            ['geonames_id' => 3488693, 'name' => 'Saint Mary', 'ascii_name' => 'St Mary', 'parish_code' => '13', 'latitude' => 18.31667000, 'longitude' => -76.90000000, 'population' => 111377, 'community_count' => 45, 'country_code' => 'JM'],
            ['geonames_id' => 3488688, 'name' => 'Saint Thomas', 'ascii_name' => 'St Thomas', 'parish_code' => '14', 'latitude' => 17.90000000, 'longitude' => -76.43333000, 'population' => 93328, 'community_count' => 56, 'country_code' => 'JM'],
            ['geonames_id' => 3489853, 'name' => 'Kingston', 'ascii_name' => 'Kingston', 'parish_code' => '17', 'latitude' => 17.96667000, 'longitude' => -76.80000000, 'population' => 90822, 'community_count' => 24, 'country_code' => 'JM'],
            ['geonames_id' => 3488997, 'name' => 'Portland', 'ascii_name' => 'Portland', 'parish_code' => '07', 'latitude' => 18.13333000, 'longitude' => -76.53333000, 'population' => 80787, 'community_count' => 43, 'country_code' => 'JM'],
            ['geonames_id' => 3488222, 'name' => 'Trelawny', 'ascii_name' => 'Trelawny', 'parish_code' => '15', 'latitude' => 18.38333000, 'longitude' => -77.63333000, 'population' => 75618, 'community_count' => 38, 'country_code' => 'JM'],
            ['geonames_id' => 3490145, 'name' => 'Hanover', 'ascii_name' => 'Hanover', 'parish_code' => '02', 'latitude' => 18.41667000, 'longitude' => -78.13333000, 'population' => 66602, 'community_count' => 35, 'country_code' => 'JM'],
        ]);
    }
}
