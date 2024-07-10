<?php

namespace App\Repositories;

use App\Models\UserInformation;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Interfaces\IUserInformationRepository;

class UserInformationRepository extends BaseRepository implements IUserInformationRepository
{
    public $model;

    /**
     * UserRepository constructor
     * 
     * @param Model $model
     */
    public function __construct(UserInformation $model)
    {
        $this->model = $model;
    }

       /**
     * Get paginated list of user information.
     *
     * @param int $limit
     * @param string $search
     * @param int $page
     * @return LengthAwarePaginator
     */
    public function getList(int $limit, string $search, int $page): LengthAwarePaginator
    {
        $columns = ['*'];

        return $this->model
            ->where(function ($query) use ($search) {
                if ($search) {
                    $query->orWhere('title', 'LIKE', '%' . $search . '%');
                }
            })
            ->paginate($limit, $columns, 'page', $page);
    }
}
