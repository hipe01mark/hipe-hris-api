<?php

namespace App\Repositories;

use App\Models\Department;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\IDepartmentRepository;

class DepartmentRepository extends BaseRepository implements IDepartmentRepository
{
    public $model;

    /**
     * Constructor
     */
    public function __construct(Department $model)
    {
        $this->model = $model;
    }
}
