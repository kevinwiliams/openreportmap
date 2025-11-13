<?php

namespace App\Listeners;

use Illuminate\Support\Arr;
use Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenAdded;

class StoreMediaExifData
{
    /**
     * Handle the event.
     */
    public function handle(MediaHasBeenAdded $event): void
    {
        $media = $event->media;

        if (! str_starts_with((string) $media->mime_type, 'image/')) {
            return;
        }

        $path = $media->getPath();

        if (! $path || ! file_exists($path)) {
            return;
        }

        if (! function_exists('exif_read_data')) {
            return;
        }

        $exif = @exif_read_data($path, null, true);

        if (! is_array($exif) || empty($exif)) {
            return;
        }

        $flattened = $this->flattenExif($exif);

        if (empty($flattened)) {
            return;
        }

        $media->setCustomProperty('exif', $flattened);
        $media->save();
    }

    private function flattenExif(array $raw): array
    {
        $sections = array_change_key_case($raw, CASE_LOWER);

        $primary = Arr::get($sections, 'ifd0', []);
        $exif = Arr::get($sections, 'exif', []);
        $gps = Arr::get($sections, 'gps', []);

        $data = array_filter([
            'camera_make' => $primary['Make'] ?? null,
            'camera_model' => $primary['Model'] ?? null,
            'lens_model' => $exif['LensModel'] ?? null,
            'exposure_time' => $exif['ExposureTime'] ?? null,
            'f_number' => $exif['FNumber'] ?? null,
            'iso' => $exif['ISOSpeedRatings'] ?? null,
            'taken_at' => $exif['DateTimeOriginal'] ?? ($primary['DateTime'] ?? null),
        ], fn ($value) => $value !== null && $value !== '');

        $gpsCoordinates = $this->gpsToDecimal($gps);

        if ($gpsCoordinates !== null) {
            $data['gps'] = $gpsCoordinates;
        }

        return $data;
    }

    private function gpsToDecimal(array $gps): ?array
    {
        $lat = Arr::get($gps, 'GPSLatitude');
        $latRef = Arr::get($gps, 'GPSLatitudeRef');
        $lng = Arr::get($gps, 'GPSLongitude');
        $lngRef = Arr::get($gps, 'GPSLongitudeRef');

        if (! $lat || ! $lng || ! $latRef || ! $lngRef) {
            return null;
        }

        $latitude = $this->toDecimal($lat, $latRef === 'S');
        $longitude = $this->toDecimal($lng, $lngRef === 'W');

        if ($latitude === null || $longitude === null) {
            return null;
        }

        return [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];
    }

    private function toDecimal(array $components, bool $negative): ?float
    {
        $parts = array_map(function ($part) {
            if (is_numeric($part)) {
                return (float) $part;
            }

            if (is_string($part) && str_contains($part, '/')) {
                [$numerator, $denominator] = array_map('floatval', explode('/', $part, 2));

                return $denominator !== 0.0 ? $numerator / $denominator : 0.0;
            }

            return null;
        }, $components);

        if (in_array(null, $parts, true)) {
            return null;
        }

        [$degrees, $minutes, $seconds] = array_pad(array_values($parts), 3, 0.0);

        $decimal = $degrees + ($minutes / 60) + ($seconds / 3600);

        return $negative ? -1 * $decimal : $decimal;
    }
}
