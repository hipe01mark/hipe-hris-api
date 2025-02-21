<?php

namespace App\Http\Controllers\Api;

use App\Traits\HandlesTransactionTrait;
use App\Constants\Define\HttpStatus;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserAddressRequest;
use App\Http\Requests\Api\UserRequest;
use App\Services\UserAddressService;
use App\Services\UserInformationService;

class UserController extends Controller
{
    use HandlesTransactionTrait;

    public $userService;
    public $userInformationService;
    public $userAddressService;

    /**
     * Constructor
     */
    public function __construct(
        UserService $userService,
        UserInformationService $userInformationService,
        UserAddressService $userAddressService
    )
    {
        $this->userService = $userService;
        $this->userInformationService = $userInformationService;
        $this->userAddressService = $userAddressService;
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
    public function store(UserRequest $request,  UserAddressRequest $addressRequest) : JsonResponse
    {
        return $this->runInTransaction(function () use ($request, $addressRequest) {
            $requests = $request->all();
            $addressRequests = $addressRequest->all();
            
            $createdUser = $this->userService->save(null, $requests);
            $createdUserInformation = $this->userInformationService->save($createdUser->id, $requests);
            $createdUserAddress = $this->userAddressService->save($createdUser->id, $addressRequests);

            $token = $createdUser->createToken('HRIS')->accessToken;
            
            return responder()
                ->success([$createdUser, $createdUserInformation, $createdUserAddress])
                ->respond(); 
        });
    }
    
    /**
     * Update a user.
     */
    public function update(int $userId, UserRequest $request, UserAddressRequest $addressRequest) : JsonResponse
    {
        return $this->runInTransaction(function () use ($userId, $request, $addressRequest) {
            $userData = $request->all();
            $addressData = $addressRequest->all();
            
            $user = $this->userService->save($userId, $userData);
            $userAddress = $this->userAddressService->save($userId, $addressData);
            $this->userInformationService->save($userId, $userData);

            return responder()
                ->success([$user->load('information'), $userAddress])
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
