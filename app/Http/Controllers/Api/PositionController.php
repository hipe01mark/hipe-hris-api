<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PositionService;
use Illuminate\Http\JsonResponse;

class PositionController extends Controller
{
    public $positionService;

    /**
     * Constructor
     */
    public function __construct(PositionService $positionService)
    {
        $this->positionService = $positionService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index() : JsonResponse
    {
        $positions = $this->positionService->get();

        return responder()
            ->success($positions)
            ->respond();
    }
}
