<?php

namespace App\Services;

use App\Repositories\Interfaces\IPositionRepository;
use Illuminate\Database\Eloquent\Collection;

class PositionService
{
    public $positionRepository;

    /**
     * Constructor
     */
    public function __construct(IPositionRepository $positionRepository)
    {
        $this->positionRepository = $positionRepository;
    }

    /**
     * Get list of positions
     */
    public function get(): Collection
    {
        return $this->positionRepository->all();
    }
}
