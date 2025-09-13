<?php
namespace App\Services;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserService{

    public function getAllUsers($filters = [])
    {
        $query = User::with('roles')
            ->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'admin');
        });

 

        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['search'] . '%');
            });
        }

   
        if (!empty($filters['role'])) {
            $query->whereHas('roles', function($q) use ($filters) {
                $q->where('name', $filters['role']);
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
    }

    /**
     * Get user by ID
     */
    public function getUserById($id)
    {
        return User::with(['roles'])->findOrFail($id);
    }

    /**
     * Create new user
     */
    public function createUser($data)
    {
        // Hash password
        $data['password'] = Hash::make($data['password']);
        
        // Set default status
        $data['status'] = 'active';

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        // Assign role by ID
        if (!empty($data['role_id'])) {
            $role = Role::findOrFail($data['role_id']);
            $user->assignRole($role->name);
        }

        return $user;
    }

    /**
     * Update user
     */
    public function updateUser($id, $data)
    {
        $user = $this->getUserById($id);

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        // Hash password if provided
        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        // Update role by ID
        if (!empty($data['role_id'])) {
            $role = Role::findOrFail($data['role_id']);
            $user->syncRoles([$role->name]);
        }

        return $user;
    }

    /**
     * Delete user
     */
    public function deleteUser($id)
    {
        $user = $this->getUserById($id);

        // Prevent deleting admin user or current user
        if ($user->hasRole('admin') && User::role('admin')->count() <= 1) {
            throw new \Exception('Cannot delete the last admin user.');
        }

        if ($user->id === auth()->id()) {
            throw new \Exception('Cannot delete your own account.');
        }

        return $user->delete();
    }

    /**
     * Get all roles for dropdown
     */
    public function getAllRoles()
    {
        return Role::where('name', '!=', 'admin')->orderBy('name')->get();
    }

}