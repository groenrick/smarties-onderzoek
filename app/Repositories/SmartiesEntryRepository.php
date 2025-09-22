<?php

declare(strict_types=1);

// app/Repositories/SmartiesEntryRepository.php
namespace App\Repositories;

use App\Models\SmartiesEntry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

class SmartiesEntryRepository implements SmartiesEntryRepositoryInterface
{
    public function __construct(
        private readonly SmartiesEntry $model
    ) {}

    public function all(): Collection
    {
        return $this->model->orderBy('date', 'desc')->get();
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->model->orderBy('date', 'desc')->paginate($perPage);
    }

    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    public function findByDate(string $date): Collection
    {
        return $this->model->whereDate('date', $date)->get();
    }

    public function findByContributor(string $contributor): Collection
    {
        return $this->model->where('contributor', $contributor)
            ->orderBy('date', 'desc')
            ->get();
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $entry = $this->find($id);

        if (!$entry) {
            return false;
        }

        return $entry->update($data);
    }

    public function delete(int $id): bool
    {
        $entry = $this->find($id);

        if (!$entry) {
            return false;
        }

        return $entry->delete();
    }

    public function query(): Builder
    {
        return $this->model->newQuery();
    }
}
