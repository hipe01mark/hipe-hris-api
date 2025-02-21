<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface IBaseRepository
{
    public function all(array $columns = ['*'], array $relations = []): Collection;
    public function paginate(
        int $perPage = 15,
        int $page = 1,
        array $relations = [],
        array $columns = ['*']
    ): LengthAwarePaginator;
    public function allTrashed(): Collection;
    public function findById(
        int $modelId, 
        array $columns = ['*'], 
        array $relations = []
    ):? Model;
    public function findItemsByColumnName(
        string $columnName,
        $columnValue,
        array $columns = ['*'], 
        array $relations = []
    ):? Collection;
    public function findTrashedById(int $modelId): ?Model;
    public function findOnlyTrashedById(int $modelId): ?Model;
    public function create(array $payload):? Model;
    public function updateOrCreate(array $conditionData = null, array $payload): ?Model;
    public function update(int $modelId, array $payload): ?Model;
    public function deleteById(int $modelId): bool;
    public function restoreById(int $modelId): ?Model;
    public function permanentlyDeleteById(int $modelId): bool;
}
