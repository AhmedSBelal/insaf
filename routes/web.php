<?php

use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});




Route::get("/permissions" , function(Request $request){
        // $query = Admin::all();
        //     if ($request->filled('search')) {
        //         $searchTerm = $request->input('search');
        //         $query->where(function ($q) use ($searchTerm) {
        //             $q->where('name', 'like', "%{$searchTerm}%")
        //                 ->orWhere('email', 'like', "%{$searchTerm}%");
        //         });
        //     }

        //     // Safely handle date_from
        //     if ($request->filled('date') && strtotime($request->date)) {
        //         $query->whereDate('created_at', '=', $request->date);
        //     }


        //     // Run the pagination query
        //     $admins = $query->orderBy('created_at', 'desc')
        //         ->paginate(10);
        //  dd($admins);

        // dd(User::first()->getPermissionsViaRoles());
             $query = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['admin', 'superadmin']);
            });
            $permissions = [];
            foreach ($query->get() as $item) {
                dd($item->getPermissionsViaRoles());
            }
    // $query = User::with(['roles'])->get();
    // $arr = [];
    // foreach ($query as $item) {
    //   dd($item->getPermissionsViaRoles());
    // }
    // dd($arr);
    // dd(Admin::first()->info->getPermissionsViaRoles());
    // dd($query->getPermissionFromRoles());
    // dd($query->first()->getPermissionFromRoles());
            // Safely handle search
            // dd(($query->findOrFail(1)->roles->first()));
//    foreach (Admin::all() as $admin) {
//     dd($admin->getRoles());
//    }
// foreach(User::all() as $user){
//     echo "$user->permission";
// }

// $users = User::with("permissions" , "roles")->get();
// dd($users);
// dd(User::findOrFail(1)->permissions);
// dd(User::role("superadmin")->get());
// dd(User::role("superadmin")->get());
// dd(Admin::role("superadmin")->get());
});