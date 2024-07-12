<?php

namespace App\Services;

use App\Models\UserAddress;

class UserAddressService
{
    public function save(int $userId, array $data)
    {
        $addressData = [
            'user_id' => $userId,
            'country' => $data['country'],
            'province' => $data['province'],
            'city' => $data['city'],
            'zip_code' => $data['zip_code'],
            'barangay' => $data['barangay'],
            'line' => $data['line'] ?? null,
            'landline' => $data['landline'] ?? null,
        ];
    
        $address = UserAddress::updateOrCreate(
            ['user_id' => $userId],
            $addressData
        );
    
        return $address;
    }
}
