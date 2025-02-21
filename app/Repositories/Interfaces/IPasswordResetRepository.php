<?php
namespace App\Repositories\Interfaces;

interface IPasswordResetRepository extends IBaseRepository 
{
    public function isResetTokenValid(string $email, string $token): bool;
}
