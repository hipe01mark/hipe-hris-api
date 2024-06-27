<?php

namespace App\Http\Controllers\Api;

use App\Constants\Define\HttpStatus;
use App\Events\LeaveDeleted;
use App\Events\LeaveUpdated;
use App\Http\Requests\Api\UserLeaveRequest;
use App\Models\UserLeave;
use App\Services\UserLeaveService;
use App\Traits\HandlesTransactionTrait;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;

class UserLeaveController extends Controller
{
    use HandlesTransactionTrait;

    public $userLeaveService;

    /**
     * Constructor
     */
    public function __construct(UserLeaveService $userLeaveService)
    {
        $this->userLeaveService = $userLeaveService;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(UserLeaveRequest $request): JsonResponse
    {
        $leaves = $this
            ->userLeaveService
            ->getByDate($request->all());

        return responder()
            ->success($leaves)
            ->respond();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserLeaveRequest $request): JsonResponse
    {
        return $this->runInTransaction(function () use ($request) {
            $userId = auth()->user()->id;
            $leave = $this->userLeaveService
                ->save($userId, $request->all());

            event(new LeaveUpdated($leave));
            return responder()
                ->success($leave)
                ->respond(HttpStatus::CREATED);
        });
    }

    /**
     * Update specified resource in storage.
     */
    public function update(UserLeaveRequest $request, UserLeave $leave): JsonResponse
    {
        return $this->runInTransaction(function () use ($request, $leave) {
            $userId = auth()->user()->id;
            $updatedLeave = $this
                ->userLeaveService  
                ->save($userId, $request->all(), $leave->id);

            event(new LeaveUpdated($updatedLeave));
            return responder()
                ->success($updatedLeave)
                ->respond();
        });
    }

    /**
     * Delete specified resource
     */
    public function destroy(UserLeaveRequest $request, UserLeave $leave): JsonResponse
    {
        return $this->runInTransaction(function () use ($leave) {
            $leaveId = $leave->id;
            $this->userLeaveService->deleteLeave($leaveId);

            event(new LeaveDeleted($leaveId));
            return responder()
                ->success()
                ->respond();
        });
    }
}
