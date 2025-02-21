<?php

namespace App\Http\Controllers\Api;

use App\Traits\HandlesTransactionTrait;
use App\Constants\Define\HttpStatus;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRequest;
use App\Services\UserInformationService;

class UserController extends Controller
{
    use HandlesTransactionTrait;

    public $userService;
    public $userInformationService;

    /**
     * Constructor
     */
    public function __construct(
        UserService $userService,
        UserInformationService $userInformationService
    )
    {
        $this->userService = $userService;
        $this->userInformationService = $userInformationService;
    }

    /**
     * Get list of user.
     */
    public function index(): JsonResponse
    {

        $users = $this->userService->getList();

        return responder()->success($users)->respond();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request) : JsonResponse
    {
        return $this->runInTransaction(function () use ($request) {
            $requests = $request->all();
            
            $createdUser = $this->userService->save(null, $requests);
            $createdUserInformation = $this->userInformationService->save($createdUser->id, $requests);
            $token = $createdUser->createToken('HRIS')->accessToken;
            
            return responder()
                ->success([$createdUser, $createdUserInformation])
                ->respond(); 
        });
    }
    
    /**
     * Update a user.
     */
    public function update(int $userId, UserRequest $request) : JsonResponse
    {
        return $this->runInTransaction(function () use ($userId, $request) {
            $requests = $request->all();
            
            $requests['user_id'] = $userId;
            
            $user = $this->userService->save($userId, $requests);
            $this->userInformationService->save($userId, $requests);
            
            return responder()
                ->success([$user->load('information')])
                ->respond();
        });
    }

     /**
     * Delete a user.
     */
    public function destroy(int $id): JsonResponse
    {
        return $this->runInTransaction(function () use ($id) {
            $this->userService->deleteById($id);
            return responder()->success(['message' => 'User deleted successfully'])->respond();
        });
    }
}
