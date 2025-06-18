<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Enums\UserRoles;
use App\Http\Controllers\APIBaseController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\AdminPermissions;
use App\Models\Admin;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminController extends APIBaseController
{

    public function overview(Request $request)
    {
        try {
            $totalAdmins = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['admin', 'superadmin']);
            })->count();

            $totalCharities = User::whereHas('roles', function ($query) {
                $query->where('name', 'charity');
            })->count();

            $totalSuppliers = User::whereHas('roles', function ($query) {
                $query->where('name', 'supplier');
            })->count();

            $recentOrders = Order::with(['charity', 'surpluses'])
                // ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            $recentNotifications = Notification::all()
                ->where('notifiable_type', Supplier::class);
                // ->orderBy('created_at', 'desc')
                // ->take(5)
                // ->get();
            $allNotifications = Notification::all()
                ->where('notifiable_type', Supplier::class);
                // ->orderBy('created_at', 'desc');
                // dd($recentOrders->first()->surpluses->first()->name);
            $totalSales = Order::all()->sum('total_price'); // Assuming you have an Order model with a total_price field
            return $this->successResponse([
                'total_admins' => $totalAdmins,
                'total_charities' => $totalCharities,
                'total_suppliers' => $totalSuppliers,
                'total_sales' => $totalSales,
                
                'recent_orders' => $recentOrders->map(function ($order) {
                    return [
                        'id' => $order->id,
                        'charity_name' => $order->charity->info->name ?? 'N/A',
                        'total_price' => $order->total_price,
                        'products' => $order->surpluses->map(function ($surplus) {
                            return [
                                'id' => $surplus->id,
                                'name' => $surplus->name ?? 'N/A',
                                'quantity' => $surplus->quantity,
                                'price' => $surplus->price,
                            ];
                        }),
                        'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                        'status' => $order->status,
                    ];
                }),
                'total_notifications_count' => $allNotifications->count(),
                'recent_notifications' => $recentNotifications,
                'all_notifications' => $allNotifications,

            ], 'Overview retrieved successfully.');

        } catch (\Exception $exception) {
            Log::error("admin overview >> \n\n" . $exception->getMessage());
            return $this->failureResponse('Something went wrong, try again later.');
        }
    }


    public function index(Request $request)
    {
        // dd($request>all());
        try {
            $query = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['admin', 'superadmin']);
            });
            // Safely handle search
            if ($request->filled('search')) {
                $searchTerm = $request->input('search');
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%")
                        ->orWhere('email', 'like', "%{$searchTerm}%");
                });
            }
            // Safely handle date_from
            if ($request->filled('date') && strtotime($request->date)) {
                $query->whereDate('created_at', '=', $request->date);
            }
            if ($request->filled('permission')) {
                // dd($request->input('permissions'));
                $permission = $request->input('permission');
                // dd($permission);
                $selectedPermission =Permission::where('name', $permission)->first();
                // dd($selectedPermission);
            //   dd($permission->roles->pluck('name')->toArray());
                $query->whereHas('roles', function ($q) use ($selectedPermission) {
                    $q->whereIn('name', $selectedPermission->roles->pluck('name')->toArray());
                });
            }
            // Run the pagination query
            $permissions = [];
            foreach ($query->get() as $item) {
                $permissions[$item->name] = $item->getPermissionsViaRoles();
            }
            $admins = $query->orderBy('created_at', 'desc')
                ->paginate(10);


            // Return success response
            return $this->successResponse([
                'admins' => $admins->load('permissions', 'roles'),
                'total' => $admins->total(),
                'permissions' => $permissions,
                'page' => $admins->currentPage(),
                'last_page' => $admins->lastPage()
            ], 'Admins retrieved successfully.');

        } catch (\Exception $exception) {
            Log::error("admin index >> \n\n" . $exception->getMessage());
            return $this->failureResponse('Something went wrong, try again later.');
        }
    }

    public function show($id)
    {

        try {
            $admin = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['admin', 'superadmin']);
            })
            ->findOrFail($id);
            
            return $this->successResponse([
                'admin' => $admin->load('roles'),
                'permissions' => $admin->getPermissionsViaRoles()
            ], 'Admin retrieved successfully.');

        } catch (\Exception $exception) {
            Log::error("admin show >> \n\n" . $exception->getMessage());
            return $this->failureResponse('Something went wrong, try again later.');
        }
    }

    // public function getAdminPermissions()
    // {
    //     dd("f");
    //     try {
    //         $permissions = AdminPermissions::values();
    //         return $this->successResponse([
    //             'permissions' => $permissions
    //         ], 'Admin permissions retrieved successfully.');
    //     } catch (\Exception $exception) {
    //         Log::error("admin getAdminPermissions >> \n\n" . $exception->getMessage());
    //         return $this->failureResponse('Something went wrong, try again later.');
    //     }
    // }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'permissions' => 'required|array',
            'permissions.*' => 'required|string|in:' . implode(',', AdminPermissions::values())
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
            ]);

            Admin::create([
                'admin_id' => $user->id,
                'type' => "admin"
            ]);


            // Check if role exists before assigning
            $adminRole = Role::where('name', 'admin')
               ->where('guard_name', 'api')
                ->first();

            if (!$adminRole) {
                throw new \Exception('Admin role does not exist');
            }

            // dd(Permission::whereIn("name" , $validated['permissions'])->get());
            $user->assignRole(Role::where("name" , UserRoles::Admin->value)->first());
            $user->syncPermissions(Permission::whereIn("name" , $validated['permissions'])->get());

            DB::commit();
// 
            return $this->successResponse([
                'admin' => $user->load('permissions', 'roles')
            ], 'Admin created successfully.');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error("admin store >> \n" . $exception->getMessage() . "\n" . $exception->getTraceAsString());
            return $this->failureResponse('Something went wrong, try again later.');
        }
    }
    public function update(Request $request, User $admin)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $admin->id,
                'phone' => 'required|string|max:20',
                'password' => 'nullable|string|min:8|confirmed',
                'permissions' => 'required|array',
                'permissions.*' => 'required|string|in:' . implode(',', AdminPermissions::values())
            ]);
            
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
            ];
            
            if (!empty($validated['password'])) {
                $updateData['password'] = bcrypt($validated['password']);
            }
            
            $admin->update($updateData);
            $admin->syncPermissions(Permission::whereIn("name" , $validated['permissions'])->get());
            // dd("gh");

            return $this->successResponse([
                'admin' => $admin->load('permissions', 'roles')
            ], 'Admin updated successfully.');

        } catch (\Exception $exception) {
            Log::error("admin update >> \n\n" . $exception->getMessage());
            return $this->failureResponse('Something went wrong, try again later.');
        }
    }

    public function destroy(User $admin)
    {
        try {
            if (!$admin->hasRole('admin')) {
                return $this->failureResponse('User is not an admin.');
            }
            // dd($admin);

            DB::beginTransaction();

            // Remove permissions and roles
            $admin->syncPermissions([]);
            $admin->syncRoles([]);

            // Delete the admin
            $admin->delete();

            DB::commit();

            return $this->successResponse([], 'Admin deleted successfully.');

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error("admin destroy >> \n\n" . $exception->getMessage());
            return $this->failureResponse('Something went wrong, try again later.');
        }
    }


    public function me(){
        return $this->successResponse([
            'admin' => auth()->user()->load('permissions', 'roles')
        ]);
    }

}
