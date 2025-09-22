<?php

declare(strict_types=1);

// app/Services/SmartiesEntry/SmartiesEntryServiceInterface.php
namespace App\Services\SmartiesEntry;

use App\DTOs\SmartiesEntryData;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface SmartiesEntryServiceInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findById(int $id): ?SmartiesEntryData;

    public function findByDate(string $date): Collection;

    public function findByContributor(string $contributor): Collection;

    public function create(array $data): SmartiesEntryData;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
