<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CountriesTableSeeder::class);
        $this->call(ParishesTableSeeder::class);
        $this->call(CommunitiesTableSeeder::class);
        $this->call(UtilityTypesTableSeeder::class);
        $this->call(DisastersTableSeeder::class);
        $this->call(ProvidersTableSeeder::class);
        $this->call(ReportsTableSeeder::class);
    }
}
