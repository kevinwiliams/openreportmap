<?php

namespace App\Http\Requests;

use App\Models\Community;
use App\Models\Report;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $maxUpload = (int) config('media-library.max_file_size', 1024 * 1024 * 10) / 1024;

        return [
            'report_type' => ['required', Rule::in(['Outage', 'Blockage', 'Damage', 'Relief'])],
            'disaster_id' => ['required', 'exists:disasters,id'],
            'country_code' => ['required', 'exists:countries,country_code'],
            'parish_code' => ['required', 'exists:parishes,parish_code'],
            'community_geonames_id' => ['required', 'exists:communities,geonames_id'],
            'precise_latitude' => ['required', 'numeric', 'between:-90,90'],
            'precise_longitude' => ['required', 'numeric', 'between:-180,180'],
            'location_description' => ['nullable', 'string', 'max:2000'],
            'severity' => ['required', Rule::in(['Low', 'Medium', 'High', 'Critical'])],
            'description' => ['nullable', 'string', 'max:500'],
            'source_url' => ['nullable', 'url', 'max:2048'],
            'source_type' => ['nullable', 'string', 'max:40'],
            'source_platform' => ['nullable', 'string', 'max:40'],
            'embed_data' => ['nullable', 'array'],
            'status' => ['nullable', 'string', 'max:30'],
            'utility_type_id' => ['required_if:report_type,Outage', 'nullable', 'exists:utility_types,id'],
            'provider_id' => ['required_if:report_type,Outage', 'nullable', 'exists:providers,id'],
            'relief_point_type' => ['required_if:report_type,Relief', 'nullable', 'string', 'max:40'],
            'relief_point_category' => ['nullable', 'string', 'max:40'],
            'contact_phone' => ['required_if:report_type,Relief', 'nullable', 'string', 'max:40'],
            'capacity' => ['nullable', 'integer', 'min:0'],
            'current_occupancy' => ['nullable', 'integer', 'min:0', 'lte:capacity'],
            'operating_hours' => ['nullable', 'string', 'max:100'],
            'reporter_display' => ['nullable', 'string', 'max:100'],
            'anonymous' => ['sometimes', 'boolean'],
            'photos' => ['nullable', 'array', 'max:4'],
            'photos.*' => ['image', 'max:'.$maxUpload],
        ];
    }

    protected function prepareForValidation(): void
    {
        $normalizedType = $this->normalizeReportType($this->input('report_type'));
        $normalizedSeverity = $this->normalizeSeverity($this->input('severity'));

        $payload = [
            'report_type' => $normalizedType,
            'severity' => $normalizedSeverity,
        ];

        if ($this->boolean('anonymous')) {
            $payload['reporter_display'] = 'Anonymous';
        }

        $this->merge($payload);

        if (is_string($this->input('embed_data'))) {
            $decoded = json_decode($this->input('embed_data'), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->merge(['embed_data' => $decoded]);
            }
        }
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $community = Community::where('geonames_id', $this->input('community_geonames_id'))->first();

            if ($community) {
                $lat = $this->floatOrNull('precise_latitude');
                $lng = $this->floatOrNull('precise_longitude');

                if ($lat !== null && $lng !== null && $community->latitude !== null && $community->longitude !== null) {
                    $latDiff = abs($lat - (float) $community->latitude);
                    $lngDiff = abs($lng - (float) $community->longitude);

                    if ($latDiff > 0.5 || $lngDiff > 0.5) {
                        $validator->errors()->add('precise_latitude', 'Coordinates must be within the selected community boundary.');
                    }
                }
            }

            $lat = $this->floatOrNull('precise_latitude');
            $lng = $this->floatOrNull('precise_longitude');
            $description = trim((string) $this->input('description'));

            if ($lat !== null && $lng !== null && $description !== '') {
                $duplicateExists = Report::query()
                    ->where('disaster_id', $this->input('disaster_id'))
                    ->where('report_type', $this->input('report_type'))
                    ->whereBetween('precise_latitude', [$lat - 0.001, $lat + 0.001])
                    ->whereBetween('precise_longitude', [$lng - 0.001, $lng + 0.001])
                    ->where(function (Builder $query) use ($description) {
                        $query->where('description', $description)
                              ->orWhereNull('description');
                    })
                    ->where('created_at', '>=', now()->sub(CarbonInterval::minutes(5)))
                    ->exists();

                if ($duplicateExists) {
                    $validator->errors()->add('description', 'A similar report was submitted in the last five minutes.');
                }
            }
        });
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        $validated['id'] = (string) Str::uuid();
        $validated['status'] = $validated['status'] ?? 'active';
        $validated['embed_data'] = $validated['embed_data'] ?? [];
        unset($validated['anonymous'], $validated['photos']);

        return $validated;
    }

    private function normalizeReportType(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = strtolower(trim($value));

        return match ($value) {
            'outage' => 'Outage',
            'blockage' => 'Blockage',
            'damage' => 'Damage',
            'relief', 'relief_point', 'relief point' => 'Relief',
            default => null,
        };
    }

    private function normalizeSeverity(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = strtolower(trim($value));

        return match ($value) {
            'low', 'minor' => 'Low',
            'medium', 'moderate' => 'Medium',
            'high', 'severe' => 'High',
            'critical' => 'Critical',
            default => null,
        };
    }

    private function floatOrNull(?string $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (float) $value;
    }
}
