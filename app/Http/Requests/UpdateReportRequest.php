<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $maxUpload = (int) config('media-library.max_file_size', 1024 * 1024 * 10) / 1024;

        return [
            'severity' => ['sometimes', Rule::in(['Low', 'Medium', 'High', 'Critical'])],
            'description' => ['sometimes', 'nullable', 'string', 'max:500'],
            'status' => ['sometimes', 'string', 'max:30'],
            'confirmation_count' => ['sometimes', 'integer', 'min:0'],
            'comment_count' => ['sometimes', 'integer', 'min:0'],
            'upvote_count' => ['sometimes', 'integer', 'min:0'],
            'downvote_count' => ['sometimes', 'integer', 'min:0'],
            'is_flagged' => ['sometimes', 'boolean'],
            'capacity' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'current_occupancy' => ['sometimes', 'nullable', 'integer', 'min:0', 'lte:capacity'],
            'operating_hours' => ['sometimes', 'nullable', 'string', 'max:100'],
            'contact_phone' => ['sometimes', 'nullable', 'string', 'max:40'],
            'provider_id' => ['sometimes', 'nullable', 'exists:providers,id'],
            'utility_type_id' => ['sometimes', 'nullable', 'exists:utility_types,id'],
            'relief_point_type' => ['sometimes', 'nullable', 'string', 'max:40'],
            'relief_point_category' => ['sometimes', 'nullable', 'string', 'max:40'],
            'location_description' => ['sometimes', 'nullable', 'string', 'max:2000'],
            'photos' => ['sometimes', 'array', 'max:4'],
            'photos.*' => ['image', 'max:'.$maxUpload],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('severity')) {
            $this->merge([
                'severity' => $this->normalizeSeverity($this->input('severity')),
            ]);
        }
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        unset($validated['photos']);

        return $validated;
    }

    private function normalizeSeverity(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return match (strtolower(trim($value))) {
            'low', 'minor' => 'Low',
            'medium', 'moderate' => 'Medium',
            'high', 'severe' => 'High',
            'critical' => 'Critical',
            default => null,
        };
    }
}
