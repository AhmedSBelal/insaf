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
            "phone" => "0123456789",
            'password' => Hash::make('123456789'),
        ]);
        $superAdmin->admin()->create([
            'id' => $superAdmin->id,
            'type' => UserRoles::SuperAdmin->value,
        ]);
        $superAdmin->assignRole(UserRoles::SuperAdmin->value);


        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.admin',
            'email_verified_at' => now(),
            "phone" => "0123456789",

            'password' => Hash::make('123456789'),
        ]);
        $admin->admin()->create([
            'id' => $admin->id,
            'type' => UserRoles::Admin->value,
        ]);
        $admin->assignRole(UserRoles::Admin->value);


        // $charity = User::create([
        //     'name' => 'Charity',
        //     "phone" => "0123456789",

        //     'email' => 'charity@charity.charity',
        //     'email_verified_at' => now(),
        //     'password' => Hash::make('123456789'),
        // ]);
        // $charity->charity()->create([
        //     'id' => $charity->id,
        //     'admin_id' => $admin->id,
        //     'phone_number' => '0123456789',
        // ]);
        // $charity->assignRole(UserRoles::Charity->value);


//         $suppler = User::create([
//             'name' => 'Supplier',
//             "phone" => "0123456789",

//             'email' => 'suppler@suppler.suppler',
//             'email_verified_at' => now(),
//             'password' => Hash::make('123456789'),
//         ]);
//         $suppler->supplier()->create([
//             'id' => $suppler->id,
//             'admin_id' => $admin->id,
// //            "phone" => "012345678923456789",

//             'phone_number' => '0123456789',
//             'status' => SupplierStatus::Approved->value,
//         ]);
//         $suppler->assignRole(UserRoles::Supplier->value);


//         $roles = [UserRoles::Admin->value, UserRoles::Supplier->value, UserRoles::Charity->value];
//         User::factory(10)->create()->each(function ($user) use ($roles) {
//             $user->assignRole(fake()->randomElement($roles));
//         });

    }
}
