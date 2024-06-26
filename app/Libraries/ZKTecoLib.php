<?php

namespace App\Libraries;

use Rats\Zkteco\Lib\ZKTeco;

class ZKTecoLib
{
    public $zkTeco;

    /**
     * Constructor
     */
    public function __construct(string $ip, int $port)
    {
        $this->zkTeco = new ZKTeco($ip, $port);
        $this->zkTeco->connect();
    }

    /**
     * Connect to device
     */
    public function connect()
    {
        return $this->zkTeco->connect();
    }

    /**
     * Connect to device
     */
    public function disconnect()
    {
        return $this->zkTeco->disconnect();
    }

    /**
     * Get attendance all employee attendance
     */
    public function getUsers()
    {
        $users = $this->zkTeco->getUser();
        return $users;
    }

    /**
     * Get attendance all employee attendance
     */
    public function getAttendances()
    {
        $attendances = $this->zkTeco->getAttendance();
        return $attendances;
    }
}
