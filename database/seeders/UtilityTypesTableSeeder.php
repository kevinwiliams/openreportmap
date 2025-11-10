<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UtilityTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('utility_types')->insert([
            ['id' => '1a50372e-b2ee-11f0-aee9-3cecefbe2405', 'type_name' => 'electricity', 'display_name' => 'Electricity', 'icon_name' => 'zap', 'sort_order' => 1],
            ['id' => '1a503a59-b2ee-11f0-aee9-3cecefbe2405', 'type_name' => 'water', 'display_name' => 'Water', 'icon_name' => 'droplet', 'sort_order' => 2],
            ['id' => '1a503c17-b2ee-11f0-aee9-3cecefbe2405', 'type_name' => 'internet', 'display_name' => 'Internet', 'icon_name' => 'wifi', 'sort_order' => 3],
            ['id' => '1a503d06-b2ee-11f0-aee9-3cecefbe2405', 'type_name' => 'phone', 'display_name' => 'Phone/Mobile', 'icon_name' => 'phone', 'sort_order' => 4],
            ['id' => '1a503dd8-b2ee-11f0-aee9-3cecefbe2405', 'type_name' => 'banking', 'display_name' => 'Banking', 'icon_name' => 'building', 'sort_order' => 5],
            ['id' => '1a504025-b2ee-11f0-aee9-3cecefbe2405', 'type_name' => 'other', 'display_name'.
- It accepts an integer and returns a boolean indicating whether the integer is a prime number.
2. *Add a test for the new function in `pymath/tests/test_math.py`.*
- The test should check that the function correctly identifies prime numbers and handles edge cases.
3. *Run the test suite.*
- I will run the tests to ensure my new function works and that I haven't introduced any regressions. I will debug any failures until all tests pass.
4. *Submit the change.*
- Once all tests pass, I will submit the change with a descriptive commit message.
' => 'Other', 'icon_name' => 'alert-circle', 'sort_order' => 6],
        ]);
    }
}
