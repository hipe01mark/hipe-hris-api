<?php

namespace App\Services;

use App\Repositories\Interfaces\IDepartmentRepository;
use Illuminate\Database\Eloquent\Collection;

class DepartmentService
{
    public $departmentRepository;

    /**
     * Constructor
     */
    public function __construct(IDepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    /**
     * Get list of departments.
     */
    public function get(): Collection
    {
        return $this->departmentRepository->all();
    }
}
