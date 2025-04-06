<?php

namespace Database\Seeders;

use App\Enums\SupplierStatus;
use App\Enums\UserRoles;
use App\Models\Charity;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::create([
            'name' => 'SuperAdmin',
            'email' => 'superadmin@admin.admin',
            'email_verified_at' => now(),
            'password' => Hash::make('123456789'),
            'role' => UserRoles::SuperAdmin->value,
        ]);
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.admin',
            'email_verified_at' => now(),
            'password' => Hash::make('123456789'),
            'role' => UserRoles::Admin->value,
        ]);
        $admin->admin()->create([
            'type' => UserRoles::Admin->value,
        ]);
        $charity = User::create([
            'name' => 'Charity',
            'email' => 'charity@charity.charity',
            'email_verified_at' => now(),
            'password' => Hash::make('123456789'),
            'role' => UserRoles::Charity->value,
        ]);
        $charity->charity()->create([
            'admin_id' => $admin->id,
            'phone_number' => '0123456789',
        ]);

        $suppler = User::create([
            'name' => 'Suppler',
            'email' => 'suppler@suppler.suppler',
            'email_verified_at' => now(),
            'password' => Hash::make('123456789'),
            'role' => UserRoles::Supplier->value,
        ]);
        $suppler->supplier()->create([
            'admin_id' => $admin->id,
            'phone_number' => '0123456789',
            'status' => SupplierStatus::Approved->value,
        ]);

    }
}
