<?php

declare(strict_types=1);

// app/Http/Controllers/Api/ContributorController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Contributor\ContributorServiceInterface;
use Illuminate\Http\JsonResponse;

class ContributorController extends Controller
{
    public function __construct(
        private readonly ContributorServiceInterface $contributorService
    ) {}

    public function index(): JsonResponse
    {
        $contributors = $this->contributorService->getAllWithCounts();

        return response()->json(['data' => $contributors]);
    }

    public function statistics(string $contributor): JsonResponse
    {
        $stats = $this->contributorService->getStatistics($contributor);

        if (!$stats) {
            return response()->json(['message' => 'Contributor not found'], 404);
        }

        return response()->json(['data' => $stats]);
    }
}
