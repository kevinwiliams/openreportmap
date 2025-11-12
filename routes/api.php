<?php

use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\DisasterController;
use App\Http\Controllers\Api\EmbedPreviewController;
use App\Http\Controllers\Api\ParishController;
use App\Http\Controllers\Api\ProviderController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\UtilityTypeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/countries', [CountryController::class, 'index']);
Route::get('/countries/{code}/parishes', [CountryController::class, 'parishes']);
Route::get('/parishes/{code}/communities', [ParishController::class, 'communities']);
Route::get('/disasters/active', [DisasterController::class, 'active']);
Route::get('/utility-types', [UtilityTypeController::class, 'index']);
Route::get('/providers', [ProviderController::class, 'index']);
Route::get('/reports', [ReportController::class, 'index']);
Route::get('/embed/preview', EmbedPreviewController::class);
Route::post('/reports', [ReportController::class, 'store'])->middleware('throttle:report-submissions');
Route::patch('/reports/{report}', [ReportController::class, 'update']);
Route::delete('/reports/{report}', [ReportController::class, 'destroy']);
