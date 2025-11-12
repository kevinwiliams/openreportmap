<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Report extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    public $incrementing = false;

    protected $keyType = 'string';

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

    protected $casts = [
        'precise_latitude' => 'float',
        'precise_longitude' => 'float',
        'embed_data' => 'array',
        'confirmation_count' => 'integer',
        'comment_count' => 'integer',
        'upvote_count' => 'integer',
        'downvote_count' => 'integer',
        'is_flagged' => 'boolean',
        'capacity' => 'integer',
        'current_occupancy' => 'integer',
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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photos')
            ->useDisk(config('media-library.disk_name', 'uploads'))
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('preview')
            ->width(1600)
            ->height(1600)
            ->performOnCollections('photos')
            ->nonQueued();

        $this->addMediaConversion('thumb')
            ->width(320)
            ->height(320)
            ->performOnCollections('photos')
            ->nonQueued();
    }
}
