<?php

declare(strict_types=1);

// app/Services/Contributor/ContributorServiceInterface.php
namespace App\Services\Contributor;

use Illuminate\Support\Collection;

interface ContributorServiceInterface
{
    public function getAllWithCounts(): Collection;

    public function getStatistics(string $contributor): ?array;
}
