<?php

namespace Database\Seeders;

use App\Enums\AdminPermissions;
use App\Enums\CharityPermissions;
use App\Enums\SupplierPermissions;
use App\Enums\UserRoles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssignPermissionsToRoles extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = DB::table('permissions')->get();
        $superAdminId = DB::table('roles')->where('name', UserRoles::SuperAdmin->value)->value('id');
        foreach ($permissions as $permission) {
            DB::table('role_has_permissions')->insert([
                'permission_id' => $permission->id,
                'role_id' => $superAdminId,
            ]);
        }
    }
}
