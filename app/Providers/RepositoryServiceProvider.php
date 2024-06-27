<?php

namespace App\Providers;

use App\Repositories\BaseRepository;
use App\Repositories\HolidayRepository;
use App\Repositories\DepartmentRepository;
use App\Repositories\Interfaces\IBaseRepository;
use App\Repositories\Interfaces\IHolidayRepository;
use App\Repositories\Interfaces\IDepartmentRepository;
use App\Repositories\Interfaces\IPasswordResetRepository;
use App\Repositories\Interfaces\IUserAttendanceRepository;
use App\Repositories\Interfaces\IUserLeaveRepository;
use App\Repositories\Interfaces\IUserRepository;
use App\Repositories\PasswordResetRepository;
use App\Repositories\UserAttendanceRepository;
use App\Repositories\UserLeaveRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(IBaseRepository::class, BaseRepository::class);
        $this->app->bind(IPasswordResetRepository::class, PasswordResetRepository::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IUserAttendanceRepository::class, UserAttendanceRepository::class);
        $this->app->bind(IUserLeaveRepository::class, UserLeaveRepository::class);
        $this->app->bind(IHolidayRepository::class, HolidayRepository::class);
        $this->app->bind(IDepartmentRepository::class, DepartmentRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
