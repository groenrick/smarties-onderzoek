<?php

declare(strict_types=1);

// app/Http/Controllers/Api/SmartiesEntryController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSmartiesEntryRequest;
use App\Http\Requests\UpdateSmartiesEntryRequest;
use App\Http\Resources\SmartiesEntryResource;
use App\Services\SmartiesEntry\SmartiesEntryServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SmartiesEntryController extends Controller
{
    public function __construct(
        private readonly SmartiesEntryServiceInterface $smartiesEntryService
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $entries = $this->smartiesEntryService->paginate(
            perPage: $request->integer('per_page', 15),
            page: $request->integer('page', 1)
        );

        return SmartiesEntryResource::collection($entries);
    }

    public function store(StoreSmartiesEntryRequest $request): JsonResponse
    {
        $entry = $this->smartiesEntryService->create($request->validated());

        return response()->json([
            'data' => new SmartiesEntryResource($entry),
            'message' => 'Smarties entry created successfully'
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $entry = $this->smartiesEntryService->findById($id);

        if (!$entry) {
            return response()->json(['message' => 'Entry not found'], 404);
        }

        return response()->json(['data' => new SmartiesEntryResource($entry)]);
    }

    public function update(UpdateSmartiesEntryRequest $request, int $id): JsonResponse
    {
        $updated = $this->smartiesEntryService->update($id, $request->validated());

        if (!$updated) {
            return response()->json(['message' => 'Entry not found'], 404);
        }

        return response()->json([
            'data' => new SmartiesEntryResource($this->smartiesEntryService->findById($id)),
            'message' => 'Entry updated successfully'
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->smartiesEntryService->delete($id);

        if (!$deleted) {
            return response()->json(['message' => 'Entry not found'], 404);
        }

        return response()->json(['message' => 'Entry deleted successfully'], 204);
    }

    public function byDate(string $date): AnonymousResourceCollection
    {
        $entries = $this->smartiesEntryService->findByDate($date);

        return SmartiesEntryResource::collection($entries);
    }

    public function byContributor(string $contributor): AnonymousResourceCollection
    {
        $entries = $this->smartiesEntryService->findByContributor($contributor);

        return SmartiesEntryResource::collection($entries);
    }
}
