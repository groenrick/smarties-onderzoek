<?php

declare(strict_types=1);

// app/Repositories/SmartiesEntryRepositoryInterface.php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

interface SmartiesEntryRepositoryInterface
{
    public function all(): Collection;

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function find(int $id): ?Model;

    public function findByDate(string $date): Collection;

    public function findByContributor(string $contributor): Collection;

    public function create(array $data): Model;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function query(): Builder;
}
