<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'disaster_type',
        'name',
        'severity',
        'start_time',
        'end_time',
        'affected_countries',
        'affected_adm1',
        'affected_adm2',
        'affected_adm3',
        'affected_adm4',
        'affected_adm5',
        'alert_message',
        'alert_message_es',
        'alert_color',
        'created_at',
        'statistics',
    ];
}
