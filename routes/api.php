<?php

// routes/api.php
use App\Http\Controllers\Api\SmartiesEntryController;
use App\Http\Controllers\Api\SmartiesStatisticsController;
use App\Http\Controllers\Api\ContributorController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Smarties entries endpoints
    Route::apiResource('entries', SmartiesEntryController::class);
    Route::get('entries/by-date/{date}', [SmartiesEntryController::class, 'byDate']);
    Route::get('entries/by-contributor/{contributor}', [SmartiesEntryController::class, 'byContributor']);

    // Statistics endpoints
    Route::prefix('statistics')->group(function () {
        Route::get('overview', [SmartiesStatisticsController::class, 'overview']);
        Route::get('color-distribution', [SmartiesStatisticsController::class, 'colorDistribution']);
        Route::get('timeline', [SmartiesStatisticsController::class, 'timeline']);
        Route::get('color/{color}', [SmartiesStatisticsController::class, 'byColor']);
    });

    // Contributors endpoints
    Route::get('contributors', [ContributorController::class, 'index']);
    Route::get('contributors/{contributor}/statistics', [ContributorController::class, 'statistics']);
});
