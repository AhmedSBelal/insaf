<?php

namespace Database\Seeders;

use App\Enums\AdminPermissions;
use App\Enums\CharityPermissions;
use App\Enums\SupplierPermissions;
use App\Enums\UserRoles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = array_merge(AdminPermissions::values(), SupplierPermissions::values(), CharityPermissions::values());
        foreach ($permissions as $permission) {
            DB::table('permissions')->insert([
                'name' => $permission,
                'guard_name' => 'api'
            ]);
        }
    }
}
