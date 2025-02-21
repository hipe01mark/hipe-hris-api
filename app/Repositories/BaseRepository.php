<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\IBaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class BaseRepository implements IBaseRepository
{
    public $model;

    CONST DEFAULT_PAGE_NAME = 'page';

    /**
     * BaseRepository constructor
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Gel all models
     */
    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model
            ->with($relations)
            ->get($columns);
    }

    /**
     * Paginate results.
     */
    public function paginate(
        int $perPage = 15,
        int $page = 1,
        array $relations = [],
        array $columns = ['*']
    ): LengthAwarePaginator
    {
        return $this->model
            ->with($relations)
            ->paginate(
                $perPage,
                $columns,
                self::DEFAULT_PAGE_NAME,
                $page
            );
    }

    /**
     * Get all trashed models
     */
    public function allTrashed(): Collection
    {
        return $this->model
            ->onlyTrashed()
            ->get();
    }

    /**
     * Find model by id
     */
    public function findById(
        int $modelId, 
        array $columns = ['*'], 
        array $relations = []
    ): ?Model 
    {
        return $this->model
            ->select($columns)
            ->with($relations)
            ->find($modelId);
    }

    /**
     * Find items by column name
     */
    public function findItemsByColumnName(
        string $columnName,
        $columnValue,
        array $columns = ['*'], 
        array $relations = []
    ): ?Collection
    {
        return $this->model
            ->select($columns)
            ->with($relations)
            ->where($columnName, $columnValue)
            ->get();
    }

    /**
     * Find trashed model by id.
     */
    public function findTrashedById(int $modelId): ?Model
    {
        return $this->model
            ->withTrashed()
            ->find($modelId);
    }

    /**
     * Find only trashed model by id.
     */
    public function findOnlyTrashedById(int $modelId): ?Model
    {
        return $this->model
            ->onlyTrashed()
            ->find($modelId);
    }

    /**
     * Store data
     */
    public function create(array $payload): ?Model
    {
        $model = $this->model;

        return $model->create($payload);
    }
    
    /**
     * Update or Store data
     */
    public function updateOrCreate(array $conditionData = null, array $payload): ?Model
    {
        $model = $this->model;
        return $model->updateOrCreate(
            $conditionData,
            $payload
        );
    }

    /** 
     * Update existing model
     */
    public function update(int $modelId, array $payload): ?Model
    {
        $model = $this->model;
        $primaryKey = $model->getKeyName();
        $model->where($primaryKey, $modelId)->update($payload);
        
        return $model;
    }
    
    /**
     * Delete data by id
     */
    public function deleteById(int $modelId): bool
    {
        $model = $this->model;
        $primaryKey = $model->getKeyName();

        return $model
            ->where($primaryKey, $modelId)
            ->delete();
    }

    /**
     * Restore data by id
     */
    public function restoreById(int $modelId): ?Model
    {
        return $this->findOnlyTrashedById($modelId)
                ->restore();
    }

    /**
     * Permanently deleted model by id.
     */
    public function permanentlyDeleteById(int $modelId): bool
    {
        return $this->findOnlyTrashedById($modelId)
                ->forceDelete();
    }
}
