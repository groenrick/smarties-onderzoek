<?php

declare(strict_types=1);

// app/Services/Contributor/ContributorService.php
namespace App\Services\Contributor;

use App\Repositories\SmartiesEntryRepositoryInterface;
use Illuminate\Support\Collection;

class ContributorService implements ContributorServiceInterface
{
    public function __construct(
        private readonly SmartiesEntryRepositoryInterface $repository
    )
    {
    }

    public function getAllWithCounts(): Collection
    {
        $entries = $this->repository->all();

        return $entries->groupBy('contributor')
            ->map(function ($contributorEntries, $name) {
                return [
                    'name' => $name,
                    'boxes_counted' => $contributorEntries->count(),
                    'total_smarties' => $contributorEntries->sum('total'),
                    'average_per_box' => round($contributorEntries->avg('total'), 1),
                    'first_entry' => $contributorEntries->min('date'),
                    'last_entry' => $contributorEntries->max('date')
                ];
            })
            ->sortByDesc('boxes_counted')
            ->values();
    }

    public function getStatistics(string $contributor): ?array
    {
        $entries = $this->repository->findByContributor($contributor);

        if ($entries->isEmpty()) {
            return null;
        }

        $colors = ['red', 'orange', 'yellow', 'green', 'blue', 'pink', 'purple', 'brown'];
        $colorPreferences = [];

        foreach ($colors as $color) {
            $colorPreferences[$color] = [
                'total' => $entries->sum($color),
                'average' => round($entries->avg($color), 2)
            ];
        }

        // Sort by average to find preferences
        $sortedColors = collect($colorPreferences)->sortByDesc('average');

        return [
            'contributor' => $contributor,
            'total_boxes' => $entries->count(),
            'total_smarties' => $entries->sum('total'),
            'average_per_box' => round($entries->avg('total'), 1),
            'color_preferences' => $colorPreferences,
            'favorite_color' => $sortedColors->keys()->first(),
            'least_favorite_color' => $sortedColors->keys()->last(),
            'date_range' => [
                'first' => $entries->min('date'),
                'last' => $entries->max('date')
            ]
        ];
    }
}



