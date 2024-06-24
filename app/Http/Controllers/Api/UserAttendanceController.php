<?php

namespace App\Http\Controllers\Api;

use App\Events\AttendanceUpdated;
use App\Http\Requests\Api\UserAttendanceRequest;
use Illuminate\Routing\Controller;
use App\Services\UserAttendanceService;
use App\Traits\HandlesTransactionTrait;
use Illuminate\Http\JsonResponse;

class UserAttendanceController extends Controller
{
    use HandlesTransactionTrait;

    public $userAttendanceService;

    /**
     * Constructor
     */
    public function __construct(
        UserAttendanceService $userAttendanceService
    )
    {
        $this->userAttendanceService = $userAttendanceService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(UserAttendanceRequest $request): JsonResponse
    {
        $filters = $request->all();
        return responder()
            ->success(
                $this->userAttendanceService
                    ->getAttendancesByDate($filters)
            )
            ->respond();
    }

    /**
     * Save time in of the user based
     * on current time.
     */
    public function timeIn(UserAttendanceRequest $request): JsonResponse
    {
        return $this->runInTransaction(function () {
            $userId = auth()->user()->id;

            $log = $this
                ->userAttendanceService
                ->timeInWFH($userId);

            $userAttendance = $log->user->load(['attendances', 'information']);
            event(new AttendanceUpdated($userAttendance));
            
            return responder()
                ->success($userAttendance)
                ->respond(); 
        });
    }

    /**
     * Save time out of the user based
     * on current time.
     */
    public function timeOut(): JsonResponse
    {
        return $this->runInTransaction(function () {
            $userId = auth()->user()->id;

            $log = $this
                ->userAttendanceService
                ->timeOutWFH($userId);

            $userAttendance = $log->user->load(['attendances', 'information']);
            event(new AttendanceUpdated($userAttendance));
            
            return responder()
                ->success($userAttendance)
                ->respond(); 
        });
    }
}
