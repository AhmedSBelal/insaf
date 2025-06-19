<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Charity;
use App\Models\Image;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Supplier;
use App\Models\Surplus;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            //  RolesSeeder::class,
            //  PermissionsSeeder::class,
            //  AssignPermissionsToRoles::class,
            //  UserSeeder::class,
            //  CategoriesSeeder::class,
            //  SurplusesSeeder::class,

        ]);

        // Charity::create([
        //     'id' => 3,
        //     'admin_id' => 2,
        //     'charity_id' => 1,
        //     'phone_number' => '0123456789',
        //     'status' => 'approved',

        // ]);
        // Charity::create([
        //     'id' => 4,
        //     'admin_id' => 2,
        //     'charity_id' => 2,
        //     'phone_number' => '0123456789',
        //     'status' => 'approved',

        // ]);
        // Order::create([
        //     'charity_id' => 3,
        //     'payment_id' => 1,
        //     'total_price' => 1000,
        //     'status' => 'Pending',
        // ]);

        // Notification::create([
        //     'id' => 2,
        //     "type" => "charity",
        //     "notifiable_type" => "App\Models\Charity",
        //     "notifiable_id" => 2,
        //     "data" => json_encode([
        //         "message" => "New order has been placed",
        //         "order_id" => 1,
        //     ]),
        //     "read_at" => null,

        // ]);

        // Image::create([
        //     'imageable_type' => 'App\Models\Supplier',
        //     'imageable_id' => 1,
        //     'type' => 'commercial_register',
        //     'url' => 'images/supplier/commercial_register.jpg',
        // ]);
        // Image::create([
        //     'imageable_type' => 'App\Models\Supplier',
        //     'imageable_id' => 1,
        //     'type' => 'health_certificate',
        //     'url' => 'images/supplier/health_certificate.jpg',
        // ])



        // Supplier::create([
        //     'supplier_id' => 4,
        //     'admin_id' => Admin::first()->id,
        //     'phone_number' => '0123456789',
        //    'status' => "Approved",
        //    "type" => "factory",
        // ]);
        // User::factory(10)->create()->each(function ($user) {
        //     $user->assignRole('supplier');
        // });
        Payment::create([
            'transaction_id' => '123456789',
            'amount' => 1000,
            'payment_method' =>  "stripe",

        ]);
       Order::create([
            'charity_id' => Charity::first()->id,
            'payment_id' => Payment::first()->id,
            'total_price' => 1000,
            'status' => 'Pending',
        ]);

        // Surplus::factory(10)->create()->each(function ($surplus) {
        //     $surplus->charity()->associate(Charity::inRandomOrder()->first());
        //     $surplus->save();
        // });
}
}