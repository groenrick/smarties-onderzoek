<?php

declare(strict_types=1);

// app/Http/Controllers/Api/SmartiesStatisticsController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Statistics\StatisticsServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SmartiesStatisticsController extends Controller
{
    public function __construct(
        private readonly StatisticsServiceInterface $statisticsService
    ) {}

    public function overview(): JsonResponse
    {
        $stats = $this->statisticsService->getOverview();

        return response()->json(['data' => $stats]);
    }

    public function colorDistribution(Request $request): JsonResponse
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $distribution = $this->statisticsService->getColorDistribution($startDate, $endDate);

        return response()->json(['data' => $distribution]);
    }

    public function timeline(Request $request): JsonResponse
    {
        $groupBy = $request->query('group_by', 'day'); // day, week, month
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $timeline = $this->statisticsService->getTimeline($groupBy, $startDate, $endDate);

        return response()->json(['data' => $timeline]);
    }

    public function byColor(string $color): JsonResponse
    {
        $validColors = ['red', 'orange', 'yellow', 'green', 'blue', 'pink', 'purple', 'brown'];

        if (!in_array($color, $validColors)) {
            return response()->json(['message' => 'Invalid color'], 400);
        }

        $stats = $this->statisticsService->getColorStatistics($color);

        return response()->json(['data' => $stats]);
    }
}
