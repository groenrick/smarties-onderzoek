<?php

declare(strict_types=1);

// app/Services/SmartiesEntry/SmartiesEntryService.php
namespace App\Services\SmartiesEntry;

use App\DTOs\SmartiesEntryData;
use App\Repositories\SmartiesEntryRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SmartiesEntryService implements SmartiesEntryServiceInterface
{
    public function __construct(
        private readonly SmartiesEntryRepositoryInterface $repository
    )
    {
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $page);
    }

    public function findById(int $id): ?SmartiesEntryData
    {
        $entry = $this->repository->find($id);

        return $entry ? SmartiesEntryData::fromModel($entry) : null;
    }

    public function findByDate(string $date): Collection
    {
        return $this->repository->findByDate($date)
            ->map(fn($entry) => SmartiesEntryData::fromModel($entry));
    }

    public function findByContributor(string $contributor): Collection
    {
        return $this->repository->findByContributor($contributor)
            ->map(fn($entry) => SmartiesEntryData::fromModel($entry));
    }

    public function create(array $data): SmartiesEntryData
    {
        // Calculate total before saving
        $data['total'] = $this->calculateTotal($data);

        $entry = $this->repository->create($data);

        return SmartiesEntryData::fromModel($entry);
    }

    public function update(int $id, array $data): bool
    {
        if (isset($data['red']) || isset($data['orange']) || isset($data['yellow']) ||
            isset($data['green']) || isset($data['blue']) || isset($data['pink']) ||
            isset($data['purple']) || isset($data['brown'])) {

            $existing = $this->repository->find($id);
            if ($existing) {
                $data['total'] = $this->calculateTotalForUpdate($existing, $data);
            }
        }

        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    private function calculateTotal(array $data): int
    {
        $colors = ['red', 'orange', 'yellow', 'green', 'blue', 'pink', 'purple', 'brown'];
        $total = 0;

        foreach ($colors as $color) {
            $total += $data[$color] ?? 0;
        }

        return $total;
    }

    private function calculateTotalForUpdate($existing, array $data): int
    {
        $colors = ['red', 'orange', 'yellow', 'green', 'blue', 'pink', 'purple', 'brown'];
        $total = 0;

        foreach ($colors as $color) {
            $total += $data[$color] ?? $existing->$color;
        }

        return $total;
    }
}
