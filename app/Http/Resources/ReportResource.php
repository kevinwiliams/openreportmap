<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    public function toArray($request): array
    {
        $properties = $this->resource->attributesToArray();

        $properties['report_type'] = $this->formatReportType($properties['report_type'] ?? null);
        $properties['severity'] = $this->formatSeverity($properties['severity'] ?? null);
        $properties['embed_data'] = $this->embed_data ?? [];
        $properties['photos'] = $this->getMedia('photos')->map(function ($media) {
            return [
                'id' => $media->uuid ?? (string) $media->id,
                'name' => $media->file_name,
                'url' => $media->getUrl('preview'),
                'thumbnail_url' => $media->getUrl('thumb'),
                'size' => $media->size,
                'mime_type' => $media->mime_type,
                'exif' => $media->getCustomProperty('exif'),
            ];
        })->values()->all();

        if ($this->relationLoaded('country') && $this->country) {
            $properties['country'] = $this->country->only(['country_code', 'name', 'flag_emoji']);
        }

        if ($this->relationLoaded('parish') && $this->parish) {
            $properties['parish'] = $this->parish->only(['parish_code', 'name']);
        }

        if ($this->relationLoaded('community') && $this->community) {
            $properties['community'] = $this->community->only(['geonames_id', 'name', 'latitude', 'longitude']);
        }

        if ($this->relationLoaded('utilityType') && $this->utilityType) {
            $properties['utility_type'] = $this->utilityType->only(['id', 'display_name', 'type_name']);
        }

        if ($this->relationLoaded('provider') && $this->provider) {
            $properties['provider'] = $this->provider->only(['id', 'provider_name', 'provider_code']);
        }

        $geometry = null;

        if (! is_null($this->precise_latitude) && ! is_null($this->precise_longitude)) {
            $geometry = [
                'type' => 'Point',
                'coordinates' => [
                    (float) $this->precise_longitude,
                    (float) $this->precise_latitude,
                ],
            ];
        }

        return [
            'type' => 'Feature',
            'id' => $this->id,
            'geometry' => $geometry,
            'properties' => $properties,
        ];
    }

    private function formatReportType(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return match (strtolower($value)) {
            'outage' => 'Outage',
            'blockage' => 'Blockage',
            'damage' => 'Damage',
            'relief', 'relief_point', 'relief point' => 'Relief',
            default => $value,
        };
    }

    private function formatSeverity(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return match (strtolower($value)) {
            'low', 'minor' => 'Low',
            'medium', 'moderate' => 'Medium',
            'high', 'severe' => 'High',
            'critical' => 'Critical',
            default => ucfirst($value),
        };
    }
}
