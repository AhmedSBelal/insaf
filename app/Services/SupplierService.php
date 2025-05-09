<?php

namespace App\Services;

use App\Enums\SupplierStatus;

class SupplierService
{
    public function registerSupplier($user, $phoneNumber)
    {
        return $user->supplier()->create([
            'id'           => $user->id,
            'supplier_id'  => $user->id,
            'admin_id'     => null,
            'phone_number' => $phoneNumber,
            'status'       => SupplierStatus::Pending
        ]);
    }
}
