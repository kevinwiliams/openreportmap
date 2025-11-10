<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommunitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('communities')->insert([
            ['geonames_id' => 11494928, 'name' => 'Cave', 'ascii_name' => 'Cave', 'parish_code' => '16', 'latitude' => 18.21019000, 'longitude' => -78.04070000, 'population' => 716, 'country_code' => 'JM'],
            ['geonames_id' => 11494945, 'name' => 'Harmony Town', 'ascii_name' => 'Harmony Town', 'parish_code' => '16', 'latitude' => 18.21987000, 'longitude' => -78.13687000, 'population' => 715, 'country_code' => 'JM'],
            ['geonames_id' => 11495405, 'name' => 'Cash Hill', 'ascii_name' => 'Cash Hill', 'parish_code' => '02', 'latitude' => 18.36920000, 'longitude' => -78.10938000, 'population' => 713, 'country_code' => 'JM'],
            ['geonames_id' => 11495557, 'name' => 'Niagara', 'ascii_name' => 'Niagara', 'parish_code' => '12', 'latitude' => 18.26534000, 'longitude' => -77.81879000, 'population' => 710, 'country_code' => 'JM'],
            ['geonames_id' => 11495704, 'name' => 'Bybrook', 'ascii_name' => 'Bybrook', 'parish_code' => '07', 'latitude' => 18.14641000, 'longitude' => -76.65726000, 'population' => 709, 'country_code' => 'JM'],
            ['geonames_id' => 11495596, 'name' => 'Content Garden', 'ascii_name' => 'Content Garden', 'parish_code' => '09', 'latitude' => 18.40977000, 'longitude' => -77.07540000, 'population' => 708, 'country_code' => 'JM'],
            ['geonames_id' => 11495265, 'name' => 'St. Leonards', 'ascii_name' => 'St. Leonards', 'parish_code' => '16', 'latitude' => 18.25533000, 'longitude' => -77.88889000, 'population' => 702, 'country_code' => 'JM'],
            ['geonames_id' => 11495551, 'name' => 'Medina', 'ascii_name' => 'Medina', 'parish_code' => '04', 'latitude' => 18.12236000, 'longitude' => -77.59643000, 'population' => 684, 'country_code' => 'JM'],
            ['geonames_id' => 11495294, 'name' => 'Porters Mountain', 'ascii_name' => 'Porters Mountain', 'parish_code' => '16', 'latitude' => 18.32909000, 'longitude' => -78.04910000, 'population' => 684, 'country_code' => 'JM'],
            ['geonames_id' => 11495425, 'name' => 'Coral Gardens', 'ascii_name' => 'Coral Gardens', 'parish_code' => '12', 'latitude' => 18.50791000, 'longitude' => -77.88034000, 'population' => 676, 'country_code' => 'JM'],
            ['geonames_id' => 11495297, 'name' => 'Grange', 'ascii_name' => 'Grange', 'parish_code' => '16', 'latitude' => 18.33161000, 'longitude' => -78.08036000, 'population' => 668, 'country_code' => 'JM'],
            ['geonames_id' => 11495378, 'name' => 'Bellas Gate', 'ascii_name' => 'Bellas Gate', 'parish_code' => '10', 'latitude' => 18.04715000, 'longitude' => -77.14906000, 'population' => 658, 'country_code' => 'JM'],
            ['geonames_id' => 11495644, 'name' => 'Sedgepond', 'ascii_name' => 'Sedgepond', 'parish_code' => '01', 'latitude' => 17.84753000, 'longitude' => -77.32638000, 'population' => 656, 'country_code' => 'JM'],
            ['geonames_id' => 11495452, 'name' => 'Ellen Street', 'ascii_name' => 'Ellen Street', 'parish_code' => '04', 'latitude' => 17.96796000, 'longitude' => -77.43931000, 'population' => 653, 'country_code' => 'JM'],
            ['geonames_id' => 11495389, 'name' => 'Bog', 'ascii_name' => 'Bog', 'parish_code' => '16', 'latitude' => 18.15631000, 'longitude' => -77.94364000, 'population' => 647, 'country_code' => 'JM'],
            ['geonames_id' => 11495455, 'name' => 'Refuge', 'ascii_name' => 'Refuge', 'parish_code' => '15', 'latitude' => 18.45101000, 'longitude' => -77.57751000, 'population' => 640, 'country_code' => 'JM'],
            ['geonames_id' => 11495027, 'name' => 'Linton Park', 'ascii_name' => 'Linton Park', 'parish_code' => '09', 'latitude' => 18.26081000, 'longitude' => -77.42221000, 'population' => 628, 'country_code' => 'JM'],
            ['geonames_id' => 11495669, 'name' => 'Tranquility', 'ascii_name' => 'Tranquility', 'parish_code' => '07', 'latitude' => 18.18028000, 'longitude' => -76.68150000, 'population' => 623, 'country_code' => 'JM'],
            ['geonames_id' => 11495700, 'name' => 'York Castle', 'ascii_name' => 'York Castle', 'parish_code' => '09', 'latitude' => 18.27515000, 'longitude' => -77.22923000, 'population' => 622, 'country_code' => 'JM'],
            ['geonames_id' => 11495153, 'name' => 'Cornwall Mountain', 'ascii_name' => 'Cornwall Mountain', 'parish_code' => '16', 'latitude' => 18.28722000, 'longitude' => -77.98674000, 'population' => 609, 'country_code' => 'JM'],
            ['geonames_id' => 11495145, 'name' => 'Sawyers', 'ascii_name' => 'Sawyers', 'parish_code' => '15', 'latitude' => 18.37880000, 'longitude' => -77.49689000, 'population' => 607, 'country_code' => 'JM'],
            ['geonames_id' => 11495492, 'name' => 'Butt-Up', 'ascii_name' => 'Butt-Up', 'parish_code' => '04', 'latitude' => 17.97313000, 'longitude' => -77.57777000, 'population' => 597, 'country_code' => 'JM'],
            ['geonames_id' => 11495221, 'name' => 'Hampden', 'ascii_name' => 'Hampden', 'parish_code' => '15', 'latitude' => 18.44217000, 'longitude' => -77.73203000, 'population' => 590, 'country_code' => 'JM'],
            ['geonames_id' => 11495309, 'name' => 'Roaring River', 'ascii_name' => 'Roaring River', 'parish_code' => '16', 'latitude' => 18.30118000, 'longitude' => -78.04324000, 'population' => 584, 'country_code' => 'JM'],
            ['geonames_id' => 11495408, 'name' => 'Castle Comfort', 'ascii_name' => 'Castle Comfort', 'parish_code' => '07', 'latitude' => 18.14217000, 'longitude' => -76.34634000, 'population' => 583, 'country_code' => 'JM'],
            ['geonames_id' => 11495124, 'name' => 'Hillside', 'ascii_name' => 'Hillside', 'parish_code' => '14', 'latitude' => 17.99619000, 'longitude' => -76.49082000, 'population' => 581, 'country_code' => 'JM'],
            ['geonames_id' => 11495466, 'name' => 'Freemans Hall', 'ascii_name' => 'Freemans Hall', 'parish_code' => '15', 'latitude' => 18.28384000, 'longitude' => -77.50180000, 'population' => 577, 'country_code' => 'JM'],
            ['geonames_id' => 11495585, 'name' => 'Banana Ground - Part of', 'ascii_name' => 'Banana Ground - Part of', 'parish_code' => '01', 'latitude' => 18.08022000, 'longitude' => -77.40971000, 'population' => 574, 'country_code' => 'JM'],
            ['geonames_id' => 11495448, 'name' => 'Durham', 'ascii_name' => 'Durham', 'parish_code' => '07', 'latitude' => 18.08566000, 'longitude' => -76.51562000, 'population' => 573, 'country_code' => 'JM'],
            ['geonames_id' => 11495293, 'name' => 'Town Head', 'ascii_name' => 'Town Head', 'parish_code' => '16', 'latitude' => 18.33898000, 'longitude' => -78.13569000, 'population' => 573, 'country_code' => 'JM'],
            ['geonames_id' => 11495454, 'name' => 'Long Road', 'ascii_name' => 'Long Road', 'parish_code' => '13', 'latitude' => 18.21337000, 'longitude' => -76.75009000, 'population' => 571, 'country_code' => 'JM'],
            ['geonames_id' => 11495547, 'name' => 'Martha Brae', 'ascii_name' => 'Martha Brae', 'parish_code' => '15', 'latitude' => 18.46960000, 'longitude' => -77.65909000, 'population' => 554, 'country_code' => 'JM'],
            ['geonames_id' => 11495696, 'name' => 'Woodsville', 'ascii_name' => 'Woodsville', 'parish_code' => '02', 'latitude' => 18.36950000, 'longitude' => -78.07217000, 'population' => 551, 'country_code' => 'JM'],
            ['geonames_id' => 11495556, 'name' => 'Mocho', 'ascii_name' => 'Mocho', 'parish_code' => '12', 'latitude' => 18.29135000, 'longitude' => -77.82580000, 'population' => 547, 'country_code' => 'JM'],
            ['geonames_id' => 11495567, 'name' => 'Mulgrave', 'ascii_name' => 'Mulgrave', 'parish_code' => '11', 'latitude' => 18.21164000, 'longitude' => -77.82018000, 'population' => 547, 'country_code' => 'JM'],
            ['geonames_id' => 11495590, 'name' => 'Petersville', 'ascii_name' => 'Petersville', 'parish_code' => '16', 'latitude' => 18.13700000, 'longitude' => -77.95046000, 'population' => 546, 'country_code' => 'JM'],
            ['geonames_id' => 11495682, 'name' => 'Waltham', 'ascii_name' => 'Waltham', 'parish_code' => '04', 'latitude' => 18.00675000, 'longitude' => -77.51873000, 'population' => 546, 'country_code' => 'JM'],
            ['geonames_id' => 11495158, 'name' => 'Belvedere', 'ascii_name' => 'Belvedere', 'parish_code' => '07', 'latitude' => 18.20772000, 'longitude' => -76.69671000, 'population' => 543, 'country_code' => 'JM'],
            ['geonames_id' => 11494937, 'name' => 'Gooden\'s River', 'ascii_name' => 'Gooden\'s River', 'parish_code' => '16', 'latitude' => 18.24223000, 'longitude' => -78.12840000, 'population' => 543, 'country_code' => 'JM'],
            ['geonames_id' => 11495662, 'name' => 'Sunning Hill', 'ascii_name' => 'Sunning Hill', 'parish_code' => '14', 'latitude' => 17.95612000, 'longitude' => -76.40931000, 'population' => 539, 'country_code' => 'JM'],
            ['geonames_id' => 11495345, 'name' => 'Enfield', 'ascii_name' => 'Enfield', 'parish_code' => '16', 'latitude' => 18.21404000, 'longitude' => -77.95270000, 'population' => 534, 'country_code' => 'JM'],
            ['geonames_id' => 11495463, 'name' => 'Garlands', 'ascii_name' => 'Garlands', 'parish_code' => '12', 'latitude' => 18.27811000, 'longitude' => -77.78908000, 'population' => 534, 'country_code' => 'JM'],
            ['geonames_id' => 11495489, 'name' => 'Joe Hut', 'ascii_name' => 'Joe Hut', 'parish_code' => '15', 'latitude' => 18.26742000, 'longitude' => -77.49616000, 'population' => 532, 'country_code' => 'JM'],
            ['geonames_id' => 11495457, 'name' => 'Fairfield', 'ascii_name' => 'Fairfield', 'parish_code' => '12', 'latitude' => 18.45079000, 'longitude' => -77.91235000, 'population' => 531, 'country_code' => 'JM'],
            ['geonames_id' => 11495321, 'name' => 'Evergreen', 'ascii_name' => 'Evergreen', 'parish_code' => '04', 'latitude' => 18.15096000, 'longitude' => -77.59965000, 'population' => 529, 'country_code' => 'JM'],
            ['geonames_id' => 11495413, 'name' => 'Chester', 'ascii_name' => 'Chester', 'parish_code' => '09', 'latitude' => 18.44786000, 'longitude' => -77.26503000, 'population' => 528, 'country_code' => 'JM'],
            ['geonames_id' => 11495597, 'name' => 'Robins Bay', 'ascii_name' => 'Robins Bay', 'parish_code' => '13', 'latitude' => 18.31236000, 'longitude' => -76.81783000, 'population' => 528, 'country_code' => 'JM'],
            ['geonames_id' => 11495318, 'name' => 'Lambs River', 'ascii_name' => 'Lambs River', 'parish_code' => '16', 'latitude' => 18.26823000, 'longitude' => -77.91414000, 'population' => 527, 'country_code' => 'JM'],
            ['geonames_id' => 11495346, 'name' => 'Porus', 'ascii_name' => 'Porus', 'parish_code' => '01', 'latitude' => 18.05202000, 'longitude' => -77.40007000, 'population' => 486, 'country_code' => 'JM'],
            ['geonames_id' => 11495441, 'name' => 'Dover', 'ascii_name' => 'Dover', 'parish_code' => '13', 'latitude' => 18.25534000, 'longitude' => -76.70811000, 'population' => 483, 'country_code' => 'JM'],
            ['geonames_id' => 11495392, 'name' => 'Brandon Hill', 'ascii_name' => 'Brandon Hill', 'parish_code' => '12', 'latitude' => 18.47767000, 'longitude' => -77.91117000, 'population' => 260, 'country_code' => 'JM'],
            ['geonames_id' => 11495217, 'name' => 'Merrywood', 'ascii_name' => 'Merrywood', 'parish_code' => '11', 'latitude' => 18.21793000, 'longitude' => -77.83773000, 'population' => 134, 'country_code' => 'JM'],
            ['geonames_id' => 11495338, 'name' => 'Beaufort', 'ascii_name' => 'Beaufort', 'parish_code' => '16', 'latitude' => 18.23417000, 'longitude' => -77.95007000, 'population' => 113, 'country_code' => 'JM'],
            ['geonames_id' => 11495459, 'name' => 'Farm Heights', 'ascii_name' => 'Farm Heights', 'parish_code' => '12', 'latitude' => 18.46869000, 'longitude' => -77.87760000, 'population' => 104, 'country_code' => 'JM'],
            ['geonames_id' => 11495506, 'name' => 'Irwin', 'ascii_name' => 'Irwin', 'parish_code' => '12', 'latitude' => 18.44949000, 'longitude' => -77.87722000, 'population' => 89, 'country_code' => 'JM'],
            ['geonames_id' => 11495438, 'name' => 'Devon Pen', 'ascii_name' => 'Devon Pen', 'parish_code' => '13', 'latitude' => 18.21561000, 'longitude' => -76.82440000, 'population' => 75, 'country_code' => 'JM'],
            ['geonames_id' => 11495569, 'name' => 'Myersville', 'ascii_name' => 'Myersville', 'parish_code' => '11', 'latitude' => 17.99699000, 'longitude' => -77.62425000, 'population' => 21, 'country_code' => 'JM'],
        ]);
    }
}
