<?php

namespace App\Repositories;

use App\Models\Position;
use App\Repositories\Interfaces\IPositionRepository;

class PositionRepository extends BaseRepository implements IPositionRepository
{
    public $model;

    /**
     * Constructor
     */
    public function __construct(Position $model)
    {
        $this->model = $model;
    }
}
