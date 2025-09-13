<?php
namespace App\Services;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RoleService{

    public function filterRoles(Request $request){

        $query = Role::with(['permissions', 'users'])->where('name', '!=', 'admin');

        // Search filter
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Permission count filter
        if ($request->filled('permission_count')) {
            switch ($request->permission_count) {
                case '0':
                    $query->has('permissions', '=', 0);
                    break;
                case '1-5':
                    $query->has('permissions', '>=', 1)->has('permissions', '<=', 5);
                    break;
                case '6-10':
                    $query->has('permissions', '>=', 6)->has('permissions', '<=', 10);
                    break;
                case '11+':
                    $query->has('permissions', '>=', 11);
                    break;
            }
        }

        // Has users filter
        if ($request->filled('has_users')) {
            if ($request->has_users === '1') {
                $query->has('users', '>', 0);
            } else {
                $query->has('users', '=', 0);
            }
        }

        $roles = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return $roles;


    }
        public function getAllRoles()
    {
        return Role::with('permissions')->get();
    }

    public function getRoleById($id)
    {
        return Role::with(['permissions', 'users'])->findOrFail($id);
    }

    public function getAllPermissions()
    {
        return Permission::all();
    }

    public function createRole(array $data)
    {
        DB::beginTransaction();
        
        try {
            // Create the role
            $role = Role::create([
                'name' => $data['name'],
                'guard_name' => 'web'
            ]);

            // Assign permissions if provided
            if (isset($data['permissions']) && is_array($data['permissions'])) {
                $permissions = Permission::whereIn('id', $data['permissions'])->get();
                $role->syncPermissions($permissions);
            }

            DB::commit();
            return $role;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function updateRole($id, array $data)
    {
        DB::beginTransaction();
        
        try {
            $role = Role::findOrFail($id);
            
            // Don't allow updating admin role name
            if ($role->name !== 'admin') {
                $role->update(['name' => $data['name']]);
            }

            // Update permissions
            if (isset($data['permissions']) && is_array($data['permissions'])) {
                $permissions = Permission::whereIn('id', $data['permissions'])->get();
                $role->syncPermissions($permissions);
            } else {
                // If no permissions provided, remove all
                $role->syncPermissions([]);
            }

            DB::commit();
            return $role;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);
        
        // Prevent deleting admin role
        if ($role->name === 'admin') {
            throw new \Exception('Cannot delete admin role');
        }

        // Check if role is assigned to any users
        if ($role->users()->count() > 0) {
            throw new \Exception('Cannot delete role that is assigned to users');
        }

        $role->delete();
        return true;
    }

    public function getRolePermissions($roleId)
    {
        $role = Role::findOrFail($roleId);
        return $role->permissions;
    }
}