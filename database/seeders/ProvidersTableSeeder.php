<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvidersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('providers')->insert([
            ['id' => '1b1a3e2b-b2ee-11f0-aee9-3cecefbe2405', 'provider_name' => 'Digicel', 'provider_code' => 'DIGICEL', 'utility_type_id' => '1a503c17-b2ee-11f0-aee9-3cecefbe2405', 'utility_type_name' => 'Internet', 'country_id' => '1a32edc4-b2ee-11f0-aee9-3cecefbe2405', 'country_name' => 'Jamaica', 'website_url' => null, 'support_phone' => null, 'support_email' => null],
            ['id' => '1b1a4295-b2ee-11f0-aee9-3cecefbe2405', 'provider_name' => 'Digicel', 'provider_code' => 'DIGICEL_PHONE', 'utility_type_id' => '1a503d06-b2ee-11f0-aee9-3cecefbe2405', 'utility_type_name' => 'Phone/Mobile', 'country_id' => '1a32edc4-b2ee-11f0-aee9-3cecefbe2405', 'country_name' => 'Jamaica', 'website_url' => null, 'support_phone' => null, 'support_email' => null],
            ['id' => '1b1a4c48-b2ee-11f0-aee9-3cecefbe2405', 'provider_name' => 'First Global Bank', 'provider_code' => 'FGB', 'utility_type_id' => '1a503dd8-b2ee-11f0-aee9-3cecefbe2405', 'utility_type_name' => 'Banking', 'country_id' => '1a32edc4-b2ee-11f0-aee9-3cecefbe2405', 'country_name' => 'Jamaica', 'website_url' => null, 'support_phone' => null, 'support_email' => null],
            ['id' => '1b1a3c63-b2ee-11f0-aee9-3cecefbe2405', 'provider_name' => 'Flow', 'provider_code' => 'FLOW', 'utility_type_id' => '1a503c17-b2ee-11f0-aee9-3cecefbe2405', 'utility_type_name' => 'Internet', 'country_id' => '1a32edc4-b2ee-11f0-aee9-3cecefbe2405', 'country_name' => 'Jamaica', 'website_url' => null, 'support_phone' => null, 'support_email' => null],
            ['id' => '1b1a411c-b2ee-11f0-aee9-3cecefbe2405', 'provider_name' => 'Flow', 'provider_code' => 'FLOW_PHONE', 'utility_type_id' => '1a503d06-b2ee-11f0-aee9-3cecefbe2405', 'utility_type_name' => 'Phone/Mobile', 'country_id' => '1a32edc4-b2ee-11f0-aee9-3cecefbe2405', 'country_name' => 'Jamaica', 'website_url' => null, 'support_phone' => null, 'support_email' => null],
            ['id' => '1b1a488c-b2ee-11f0-aee9-3cecefbe2405', 'provider_name' => 'JMMB', 'provider_code' => 'JMMB', 'utility_type_id' => '1a503dd8-b2ee-11f0-aee9-3cecefbe2405', 'utility_type_name' => 'Banking', 'country_id' => '1a32edc4-b2ee-11f0-aee9-3cecefbe2405', 'country_name' => 'Jamaica', 'website_url' => null, 'support_phone' => null, 'support_email' => null],
            ['id' => '1b1a32c5-b2ee-11f0-aee9-3cecefbe2405', 'provider_name' => 'JPS (Jamaica Public Service)', 'provider_code' => 'JPS', 'utility_type_id' => '1a50372e-b2ee-11f0-aee9-3cecefbe2405', 'utility_type_name' => 'Electricity', 'country_id' => '1a32edc4-b2ee-11f0-aee9-3cecefbe2405', 'country_name' => 'Jamaica', 'website_url' => null, 'support_phone' => null, 'support_email' => null],
            ['id' => '1b1a459f-b2ee-11f0-aee9-3cecefbe2405', 'provider_name' => 'NCB (National Commercial Bank)', 'provider_code' => 'NCB', 'utility_type_id' => '1a503dd8-b2ee-11f0-aee9-3cecefbe2405', 'utility_type_name' => 'Banking', 'country_id' => '1a32edc4-b2ee-11f0-aee9-3cecefbe2405', 'country_name' => 'Jamaica', 'website_url' => null, 'support_phone' => null, 'support_email' => null],
            ['id' => '1b1a38dd-b2ee-11f0-aee9-3cecefbe2405', 'provider_name' => 'NWC (National Water Commission)', 'provider_code' => 'NWC', 'utility_type_id' => '1a503a59-b2ee-11f0-aee9-3cecefbe2405', 'utility_type_name' => 'Water', 'country_id' => '1a32edc4-b2ee-11f0-aee9-3cecefbe2405', 'country_name' => 'Jamaica', 'website_url' => null, 'support_phone' => null, 'support_email' => null],
            ['id' => '1b1a3691-b2ee-11f0-aee9-3cecefbe2405', 'provider_name' => 'Other', 'provider_code' => 'OTHER_ELEC', 'utility_type_id' => '1a50372e-b2ee-11f0-aee9-3cecefbe2405', 'utility_type_name' => 'Electricity', 'country_id' => '1a32edc4-b2ee-11f0-aee9-3cecefbe2405', 'country_name' => 'Jamaica', 'website_url' => null, 'support_phone' => null, 'support_email' => null],
            ['id' => '1b1a3a96-b2ee-11f0-aee9-3cecefbe2405', 'provider_name' => 'Other', 'provider_code' => 'OTHER_WATER', 'utility_type_id' => '1a503a59-b2ee-11f0-aee9-3cecefbe2405', 'utility_type_name' => 'Water', 'country_id' => '1a32edc4-b2ee-11f0-aee9-3cecefbe2405', 'country_name' => 'Jamaica', 'website_url' => null, 'support_phone' => null, 'support_email' => null],
            ['id' => '1b1a3f9c-b2ee-11f0-aee9-3cecefbe2405', 'provider_name' => 'Other', 'provider_code' => 'OTHER_INTERNET', 'utility_type_id' => '1a503c17-b2ee-11f0-aee9-3cecefbe2405', 'utility_type_name' => 'Internet', 'country_id' => '1a32edc4-b2ee-11f0-aee9-3cecefbe2405', 'country_name' => 'Jamaica', 'website_url' => null, 'support_phone' => null, 'support_email' => null],
            ['id' => '1b1a4414-b2ee-11f0-aee9-3cecefbe2405', 'provider_name' => 'Other', 'provider_code' => 'OTHER_PHONE', 'utility_type_id' => '1a503d06-b2ee-11f0-aee9-3cecefbe2405', 'utility_type_name' => 'Phone/Mobile', 'country_id' => '1a32edc4-b2ee-11f0-aee9-3cecefbe2405', 'country_name' => 'Jamaica', 'website_url' => null, 'support_phone' => null, 'support_email' => null],
            ['id' => '1b1a4dea-b2ee-11f0-aee9-3cecefbe2405', 'provider_name' => 'Other', 'provider_code' => 'OTHER_BANK', 'utility_type_id' => '1a503dd8-b2ee-11f0-aee9-3cecefbe2405', 'utility_type_name' => 'Banking', 'country_id' => '1a32edc4-b2ee-11f0-aee9-3cecefbe2405', 'country_name' => 'Jamaica', 'website_url' => null, 'support_phone' => null, 'support_email' => null],
            ['id' => '1b1a4a06-b2ee-11f0-aee9-3cecefbe2405', 'provider_name' => 'Sagicor Bank', 'provider_code' => 'SAGICOR', 'utility_type_id' => '1a503dd8-b2ee-11f0-aee9-3cecefbe2405', 'utility_type_name' => 'Banking', 'country_id' => '1a32edc4-b2ee-11f0-aee9-3cecefbe2405', 'country_name' => 'Jamaica', 'website_url' => null, 'support_phone' => null, 'support_email' => null],
            ['id' => '1b1a4708-b2ee-11f0-aee9-3cecefbe2405', 'provider_name' => 'Scotiabank', 'provider_code' => 'SCOTIA', 'utility_type_id' => '1a503dd8-b2ee-11f0-aee9-3cecefbe2405', 'utility_type_name' => 'Banking', 'country_id' => '1a32edc4-b2ee-11f0-aee9-3cecefbe2405', 'country_name' => 'Jamaica', 'website_url' => null, 'support_phone' => null, 'support_email' => null],
        ]);
    }
}
