<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'report_type',
        'country_code',
        'parish_code',
        'community_geonames_id',
        'utility_type_id',
        'provider_id',
        'disaster_id',
        'precise_latitude',
        'precise_longitude',
        'location_description',
        'severity',
        'description',
        'source_url',
        'source_type',
        'source_platform',
        'embed_data',
        'status',
        'confirmation_count',
        'comment_count',
        'upvote_count',
        'downvote_count',
        'is_flagged',
        'relief_point_type',
        'relief_point_category',
        'contact_phone',
        'capacity',
        'current_occupancy',
        'operating_hours',
        'created_at',
        'updated_at',
        'reporter_display',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code', 'country_code');
    }

    public function parish()
    {
        return $this->belongsTo(Parish::class, 'parish_code', 'parish_code');
    }

    public function community()
    {
        return $this->belongsTo(Community::class, 'community_geonames_id', 'geonames_id');
    }

    public function utilityType()
    {
        return $this->belongsTo(UtilityType::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function disaster()
    {
        return $this->belongsTo(Disaster::class);
    }
}
