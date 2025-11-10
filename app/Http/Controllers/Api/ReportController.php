<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::query();

        if ($request->has('disaster_id')) {
            $query->where('disaster_id', $request->disaster_id);
        }

        if ($request->has('bounds')) {
            $bounds = explode(',', $request->bounds);
            $query->where('precise_latitude', '>=', $bounds[1])
                ->where('precise_latitude', '<=', $bounds[0])
                ->where('precise_longitude', '>=', $bounds[3])
                ->where('precise_longitude', '<=', $bounds[2]);
        }

        return $query->get();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'report_type' => 'required|in:Outage,Blockage,Damage,Relief',
            'disaster_id' => 'required|exists:disasters,id',
            'country_code' => 'required|exists:countries,country_code',
            'parish_code' => 'required|exists:parishes,parish_code',
            'community_geonames_id' => 'required|exists:communities,geonames_id',
            'precise_latitude' => 'required|numeric|between:-90,90',
            'precise_longitude' => 'required|numeric|between:-180,180',
            'severity' => 'required|in:Low,Medium,High,Critical',
            'utility_type_id' => 'required_if:report_type,Outage|exists:utility_types,id',
            'provider_id' => 'required_if:report_type,Outage|exists:providers,id',
            'relief_point_type' => 'required_if:report_type,Relief|string|max:40',
            'capacity' => 'nullable|integer|min:0',
            'current_occupancy' => 'nullable|integer|min:0|lte:capacity',
            'operating_hours' => 'nullable|string|max:100',
            'contact_phone' => 'required_if:report_type,Relief|max:40',
            'source_url' => 'nullable|url|max:255',
            'reporter_display' => 'nullable|string|max:100',
        ]);

        $report = Report::create($validatedData);

        return response()->json($report, 201);
    }

    public function update(Request $request, Report $report)
    {
        $validatedData = $request->validate([
            'severity' => 'in:Low,Medium,High,Critical',
            'description' => 'string',
            'status' => 'in:Open,Resolved,Deleted',
            'confirmation_count' => 'integer',
            'upvote_count' => 'integer',
            'downvote_count' => 'integer',
            'is_flagged' => 'boolean',
            'capacity' => 'integer|min:0',
            'current_occupancy' => 'integer|min:0|lte:capacity',
            'operating_hours' => 'string|max:100',
            'contact_phone' => 'max:40',
            'provider_id' => 'exists:providers,id',
            'utility_type_id' => 'exists:utility_types,id',
            'relief_point_type' => 'string|max:40',
            'relief_point_category' => 'string|max:40',
            'location_description' => 'string',
        ]);

        $report->update($validatedData);

        return response()->json($report);
    }

    public function destroy(Report $report)
    {
        $report->update(['status' => 'Deleted']);

        return response()->json(null, 204);
    }
}
