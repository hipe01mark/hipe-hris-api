<?php

namespace App\Http\Controllers\Api;

use App\Constants\LeaveStatuses;
use App\Events\LeaveUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LeaveActionRequest;
use App\Models\UserLeave;
use App\Services\UserLeaveService;
use App\Traits\HandlesTransactionTrait;
use Illuminate\Http\JsonResponse;

class LeaveActionController extends Controller
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
     * Approve user leave request
     */
    public function approve(LeaveActionRequest $request, UserLeave $leave): JsonResponse
    {
        return $this->runInTransaction(function () use ($leave) {
            $approvedLeaved = $this->userLeaveService
                ->changeStatus(
                    auth()->user()->id,
                    $leave->id, 
                    LeaveStatuses::APPROVED
                );

            event(new LeaveUpdated($approvedLeaved));
            
            return responder()
                ->success($approvedLeaved)
                ->respond();
        });
    }

    /**
     * Decline user leave request
     */
    public function decline(LeaveActionRequest $request, UserLeave $leave): JsonResponse
    {
        return $this->runInTransaction(function () use ($leave) {
            $declinedLeave = $this
                ->userLeaveService
                ->changeStatus(
                    auth()->user()->id,
                    $leave->id, 
                    LeaveStatuses::DECLINED
                );

            event(new LeaveUpdated($declinedLeave));
            
            return responder()
                ->success($declinedLeave)
                ->respond();
        });
    }
}
