<?php

declare(strict_types=1);

// app/Services/Statistics/StatisticsService.php
namespace App\Services\Statistics;

use App\Repositories\SmartiesEntryRepositoryInterface;
use Illuminate\Support\Carbon;

class StatisticsService implements StatisticsServiceInterface
{
    private const COLORS = ['red', 'orange', 'yellow', 'green', 'blue', 'pink', 'purple', 'brown'];

    public function __construct(
        private readonly SmartiesEntryRepositoryInterface $repository
    )
    {
    }

    public function getOverview(): array
    {
        $entries = $this->repository->all();

        if ($entries->isEmpty()) {
            return $this->emptyOverview();
        }

        $totalBoxes = $entries->count();
        $totalSmartiesSum = $entries->sum('total');
        $avgPerBox = round($totalSmartiesSum / $totalBoxes, 1);

        // Calculate most common color
        $colorTotals = [];
        foreach (self::COLORS as $color) {
            $colorTotals[$color] = $entries->sum($color);
        }

        $mostCommonColor = array_keys($colorTotals, max($colorTotals))[0];
        $mostCommonAvg = round($colorTotals[$mostCommonColor] / $totalBoxes, 2);

        return [
            'total_boxes' => $totalBoxes,
            'total_smarties' => $totalSmartiesSum,
            'average_per_box' => $avgPerBox,
            'most_common_color' => [
                'color' => $mostCommonColor,
                'average' => $mostCommonAvg,
                'total' => $colorTotals[$mostCommonColor]
            ],
            'last_entry_date' => $entries->max('date')
        ];
    }

    public function getColorDistribution(?string $startDate = null, ?string $endDate = null): array
    {
        $query = $this->repository->query();

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        $entries = $query->get();

        if ($entries->isEmpty()) {
            return ['distribution' => [], 'total_boxes' => 0, 'total_smarties' => 0];
        }

        $distribution = [];
        $totalSmartiesSum = 0;

        foreach (self::COLORS as $color) {
            $colorTotal = $entries->sum($color);
            $totalSmartiesSum += $colorTotal;

            $distribution[$color] = [
                'count' => $colorTotal,
                'average' => round($colorTotal / $entries->count(), 2),
                'percentage' => 0 // Will calculate after we have the total
            ];
        }

        // Calculate percentages
        foreach (self::COLORS as $color) {
            $distribution[$color]['percentage'] = round(
                ($distribution[$color]['count'] / $totalSmartiesSum) * 100,
                1
            );
        }

        return [
            'distribution' => $distribution,
            'total_boxes' => $entries->count(),
            'total_smarties' => $totalSmartiesSum,
            'date_range' => [
                'start' => $startDate ?? $entries->min('date'),
                'end' => $endDate ?? $entries->max('date')
            ]
        ];
    }

    public function getTimeline(string $groupBy = 'day', ?string $startDate = null, ?string $endDate = null): array
    {
        $query = $this->repository->query();

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        $entries = $query->orderBy('date')->get();

        if ($entries->isEmpty()) {
            return ['timeline' => [], 'group_by' => $groupBy];
        }

        $grouped = $this->groupEntriesByPeriod($entries, $groupBy);

        $timeline = [];
        foreach ($grouped as $period => $periodEntries) {
            $avgTotal = round($periodEntries->avg('total'), 1);
            $timeline[] = [
                'period' => $period,
                'boxes_count' => $periodEntries->count(),
                'average_total' => $avgTotal,
                'min' => $periodEntries->min('total'),
                'max' => $periodEntries->max('total')
            ];
        }

        return [
            'timeline' => $timeline,
            'group_by' => $groupBy
        ];
    }

    public function getColorStatistics(string $color): array
    {
        if (!in_array($color, self::COLORS)) {
            return ['error' => 'Invalid color'];
        }

        $entries = $this->repository->all();

        if ($entries->isEmpty()) {
            return $this->emptyColorStats($color);
        }

        $colorValues = $entries->pluck($color);
        $totalBoxes = $entries->count();
        $colorTotal = $colorValues->sum();

        // Calculate variance and standard deviation
        $average = $colorTotal / $totalBoxes;
        $variance = $colorValues->map(function ($value) use ($average) {
                return pow($value - $average, 2);
            })->sum() / $totalBoxes;

        $stdDev = sqrt($variance);

        return [
            'color' => $color,
            'total' => $colorTotal,
            'average' => round($average, 2),
            'min' => $colorValues->min(),
            'max' => $colorValues->max(),
            'standard_deviation' => round($stdDev, 2),
            'consistency_score' => round(100 - ($stdDev / $average * 100), 1), // Higher is more consistent
            'occurrences' => [
                'zero' => $colorValues->filter(fn($v) => $v === 0)->count(),
                'above_average' => $colorValues->filter(fn($v) => $v > $average)->count(),
                'below_average' => $colorValues->filter(fn($v) => $v < $average)->count()
            ]
        ];
    }

    private function groupEntriesByPeriod($entries, string $groupBy)
    {
        return $entries->groupBy(function ($entry) use ($groupBy) {
            $date = Carbon::parse($entry->date);

            return match ($groupBy) {
                'week'  => $date->format('Y-W'),
                'month' => $date->format('Y-m'),
                'year'  => $date->format('Y'),
                default => $date->format('Y-m-d'), // day
            };
        });
    }

    private function emptyOverview(): array
    {
        return [
            'total_boxes' => 0,
            'total_smarties' => 0,
            'average_per_box' => 0,
            'most_common_color' => null,
            'last_entry_date' => null
        ];
    }

    private function emptyColorStats(string $color): array
    {
        return [
            'color' => $color,
            'total' => 0,
            'average' => 0,
            'min' => 0,
            'max' => 0,
            'standard_deviation' => 0,
            'consistency_score' => 0,
            'occurrences' => [
                'zero' => 0,
                'above_average' => 0,
                'below_average' => 0
            ]
        ];
    }
}
