<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'provider_name',
        'provider_code',
        'utility_type_id',
        'utility_type_name',
        'country_id',
        'country_name',
        'website_url',
        'support_phone',
        'support_email',
    ];

    public function utilityType()
    {
        return $this->belongsTo(UtilityType::class);
    }
}
