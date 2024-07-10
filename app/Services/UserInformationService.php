<?php
namespace App\Services;

use App\Models\UserInformation;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Interfaces\IUserInformationRepository;

class UserInformationService 
{
    CONST PROBATIONARY_STATUS = 1;

    public $userInformationRepository;
    
    /**
     * Student information service constructor
     */
    public function __construct(IUserInformationRepository $iUserInformationRepository)
    {
        $this->userInformationRepository = $iUserInformationRepository;
    }

    /**
     * Create student information.
     */
    public function save(int $userId, array $information) : UserInformation
    {
        $data = [
            'user_id' => $userId,
            'first_name' => ucfirst($information['first_name']),
            'middle_name' => ucfirst($information['middle_name']),
            'last_name' => ucfirst($information['last_name']),
            'gender' => $information['gender'],
            'mobile_number' => $information['mobile_number'],

            'branch_id' => $information['branch_id'],
            'department_id' => $information['department_id'],
            'position_id' => $information['position_id'],
            'status_id' => $information['status_id'],

            'birth_date' => $information['birth_date'],
            'hired_date' => now(),
            'resigned_date' => $information['resigned_date'] ?? null,
            'nationality' => $information['nationality'],
            'religion' => $information['religion'],
            'marital_status' => $information['marital_status'],
        ];
        
        return $this
            ->userInformationRepository
            ->updateOrCreate(
                ['user_id' => $userId],
                $data
            );
    }

    /**
     * Get list of user information.
     */
    public function getList(): LengthAwarePaginator 
    {
        $page = request()->input('page', 1);
        $limit = request()->input('limit', 6);
        $search = request()->input('search', '');

        return $this->userInformationRepository
            ->getList($limit, $search, $page);
    }
}
