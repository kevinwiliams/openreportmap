<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DisastersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('disasters')->insert([
            [
                'id' => '1',
                'disaster_type' => 'hurricane',
                'name' => 'Hurricane Melissa',
                'severity' => 'emergency',
                'start_time' => '2025-10-26 00:00:00',
                'end_time' => '2025-11-29 23:59:59',
                'affected_countries' => '["JM","HT","CU","BS"]',
                'affected_adm1' => '[]',
                'affected_adm2' => '[]',
                'affected_adm3' => '[]',
                'affected_adm4' => '[]',
                'affected_adm5' => '[]',
                'alert_message' => 'CATEGORY 5 HURRICANE MELISSA - EMERGENCY: Maximum sustained winds 160 mph. Up to 40 inches of rain expected in eastern Jamaica. Catastrophic flash flooding and landslides likely. Report ALL road blockages, flooding, infrastructure damage, and safety hazards immediately.',
                'alert_message_es' => 'HURACÁN MELISSA CATEGORÍA 5 - EMERGENCIA: Vientos sostenidos máximos de 260 km/h. Se esperan hasta 1 metro de lluvia en el este de Jamaica. Inundaciones repentinas catastróficas y deslizamientos de tierra probables. Reporte TODOS los bloqueos de carreteras, inundaciones, daños a infraestructura y peligros de seguridad inmediatamente.',
                'alert_color' => 'red',
                'created_at' => '2025-10-27 03:57:38',
                'statistics' => '{"total_reports":110,"outage_count":"25","blockage_count":"52","damage_count":"30","relief_point_count":"3","critical_count":"0"}',
            ]
        ]);
    }
}
