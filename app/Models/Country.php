<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_code',
        'name',
        'flag_emoji',
        'is_active',
        'launch_date',
        'total_adm1',
        'total_adm2',
        'map_center_lat',
        'map_center_lng',
        'map_zoom_level',
        'primary_language',
        'secondary_language',
        'timezone',
        'disaster_mode_active',
    ];

    public function parishes()
    {
        return $this->hasMany(Parish::class, 'country_code', 'country_code');
    }
}
