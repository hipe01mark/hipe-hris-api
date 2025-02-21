<?php

namespace App\Console\Commands;

use App\Constants\Locations;
use App\Libraries\ZKTecoLib;
use App\Services\UserService;
use App\Services\UserAttendanceService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class ImportUserAndAttendance extends Command
{
    public $ip;
    public $port;
    public $startDate;
    public $userService;
    public $userAttendanceService;

    CONST DOMAIN_ADDRESS = '@bpoc.co.jp';
    CONST DEFAULT_PASSWORD = 'hipe1108';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:user-and-attendance {--startDate= : The start date for importing} {--ip= : IP Address} {--port= : Port}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importing data of user and its attendance to save it to the database';

    /**
     * Create a new command instance.
     */
    public function __construct(
        UserService $userService,
        UserAttendanceService $userAttendanceService
    )
    {
        parent::__construct();
        set_time_limit(0);

        $this->userService = $userService;
        $this->userAttendanceService = $userAttendanceService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->ip = $this->option('ip');
        $this->port = (int)$this->option('port');
        $this->startDate = $this->option('startDate');

        $zktecoLib = new ZKTecoLib($this->ip, $this->port);
        $users = $zktecoLib->getUsers();
        $attendances = $zktecoLib->getAttendances();
        $filterAttendances = $this->filterAttendances($attendances);

        foreach ($users as $user) {
            $userId = (int)$user['userid'];
            $this->saveUser($userId, $user);
            $timeInsAndTimeOuts = $this->getTimeInsAndTimeOuts($userId, $filterAttendances);

            $this->saveUserAttendance($userId, $timeInsAndTimeOuts);
        }

        $this->info('User and attendance imported successfully!');
    }
    
    /**
     * Save user
     */
    private function saveUser(int $userId, $user): void
    {
        $email = $userId . self::DOMAIN_ADDRESS;
        $userArray = [
            'user_id' => $userId,
            'email' => $email,
            'password' => self::DEFAULT_PASSWORD,
            'temporary_name' => $user['name']
        ];

        $this->userService->save($userId, $userArray);
    }

    /**
     * Save user attendance
     */
    private function saveUserAttendance(int $userId, Collection $timeInsAndTimeOuts): void
    {
        if ($timeInsAndTimeOuts->count() > 0) {
            foreach ($timeInsAndTimeOuts as $key => $timeInsAndTimeOut) {
                if ($timeInsAndTimeOut->count() > 0) {
                    $attendanceArray = [
                        'id' => $timeInsAndTimeOut->first()['uid'],
                        'user_id' => $userId,
                        'date' => Carbon::parse($timeInsAndTimeOut->first()['timestamp'])->format('Y-m-d'),
                        'time_in' => $timeInsAndTimeOut->first()['timestamp'],
                        'time_out' => $this->getTimeOut($timeInsAndTimeOut),
                        'in_location' => Locations::OFFICE,
                        'out_location' => Locations::OFFICE
                    ];
    
                    $this->userAttendanceService
                        ->userAttendanceRepository
                        ->updateOrCreate([
                            'id' => $attendanceArray['id'],
                            'date' => Carbon::parse($timeInsAndTimeOut->first()['timestamp'])->format('Y-m-d')
                        ], $attendanceArray);
                }
            }
        }
    }

    /**
     * Get logs based on inputtedstart date.
     */
    private function filterAttendances(array $attendances): array
    {
        $filterAttendances = Collection::make($attendances)->filter(
            function ($item) {
                $timestamp = $this->startDate ?? Carbon::now()->format('Y-m-d');
                return Carbon::parse($item['timestamp'])->format('Y-m-d') >= Carbon::parse($timestamp)->format('Y-m-d');
            }
        )->all();

        return $filterAttendances;
    }

    /**
     * Get the first log for time in and last log for time out.
     */
    private function getTimeInsAndTimeOuts(int $userId, array $filterAttendances): Collection
    {
        $timeInsAndTimeOuts = collect();

        $groupedByDate = collect($filterAttendances)->groupBy(function ($item) {
            return Carbon::parse($item['timestamp'])->format('Y-m-d');
        });

        foreach ($groupedByDate as $date => $attendances) {
            $userAttendances = $attendances->filter(function ($item) use ($userId) {
                return $item['id'] == $userId;
            });

            if ($userAttendances->isNotEmpty()) {
                $firstLog = $userAttendances->sortBy('timestamp')->first();
                $lastLog = $userAttendances->sortByDesc('timestamp')->first();
                
                $timeInsAndTimeOuts[$date] = collect(['time_in' => $firstLog, 'time_out' => $lastLog]);
            }
        }

        return $timeInsAndTimeOuts;
    }

    /**
     * Get last time out
     */
    private function getTimeOut(Collection $timeInsAndTimeOut): ?string
    {
        $first = $timeInsAndTimeOut->first()['timestamp'];
        $last = $timeInsAndTimeOut->last()['timestamp'];

        return $timeInsAndTimeOut->count() > 1 && ($first !== $last) ? $last : null;
    }
}
