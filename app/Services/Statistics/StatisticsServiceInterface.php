<?php

declare(strict_types=1);

// app/Services/Statistics/StatisticsServiceInterface.php
namespace App\Services\Statistics;

interface StatisticsServiceInterface
{
    public function getOverview(): array;

    public function getColorDistribution(?string $startDate = null, ?string $endDate = null): array;

    public function getTimeline(string $groupBy = 'day', ?string $startDate = null, ?string $endDate = null): array;

    public function getColorStatistics(string $color): array;
}
