<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Http\Resources\ReportResource;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        if (! $request->filled('disaster_id')) {
            throw ValidationException::withMessages([
                'disaster_id' => 'The disaster_id query parameter is required.',
            ]);
        }

        $query = Report::query()
            ->with(['country', 'parish', 'community', 'utilityType', 'provider'])
            ->where('disaster_id', $request->string('disaster_id'))
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhereRaw('LOWER(status) != ?', ['deleted']);
            })
            ->orderByDesc('created_at');

        if ($request->filled('bounds')) {
            $bounds = array_map('floatval', explode(',', (string) $request->bounds));

            if (count($bounds) === 4) {
                [$north, $south, $east, $west] = $bounds;

                $query->whereNotNull('precise_latitude')
                    ->whereNotNull('precise_longitude')
                    ->whereBetween('precise_latitude', [$south, $north])
                    ->whereBetween('precise_longitude', [$west, $east]);
            }
        }

        $features = ReportResource::collection($query->get())->resolve();

        return [
            'type' => 'FeatureCollection',
            'features' => $features,
        ];
    }

    public function store(StoreReportRequest $request)
    {
        $data = $request->validated();

        $report = DB::transaction(function () use ($data, $request) {
            $timestamps = now();

            $report = Report::create(array_merge($data, [
                'created_at' => $timestamps,
                'updated_at' => $timestamps,
            ]));

            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $report->addMedia($photo)
                        ->preservingOriginal()
                        ->toMediaCollection('photos');
                }
            }

            return $report->fresh(['country', 'parish', 'community', 'utilityType', 'provider']);
        });

        return (new ReportResource($report))->response()->setStatusCode(201);
    }

    public function update(UpdateReportRequest $request, Report $report)
    {
        $data = $request->validated();

        $report = DB::transaction(function () use ($data, $report, $request) {
            if (! empty($data)) {
                $report->fill($data);
            }

            $report->updated_at = now();
            $report->save();

            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $report->addMedia($photo)
                        ->preservingOriginal()
                        ->toMediaCollection('photos');
                }
            }

            return $report->fresh(['country', 'parish', 'community', 'utilityType', 'provider']);
        });

        return new ReportResource($report);
    }

    public function destroy(Report $report)
    {
        $report->update([
            'status' => 'Deleted',
            'updated_at' => now(),
        ]);

        return response()->json(null, 204);
    }
}
