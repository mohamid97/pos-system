<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RoleService;
use Illuminate\Support\Facades\Validator;



class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;

    }

    public function index(Request $request)
    {

        $roles = $this->roleService->filterRoles($request); 

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = $this->roleService->getAllPermissions();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $this->roleService->createRole($request->all());
            return redirect()->route('roles.index')
                ->with('success', 'Role created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating role: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $role = $this->roleService->getRoleById($id);
        return view('roles.show', compact('role'));
    }

    public function edit($id)
    {
        $role = $this->roleService->getRoleById($id);
        $permissions = $this->roleService->getAllPermissions();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $role = $this->roleService->getRoleById($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $this->roleService->updateRole($id, $request->all());
            return redirect()->route('roles.index')
                ->with('success', 'Role updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating role: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $this->roleService->deleteRole($id);
            return redirect()->route('roles.index')
                ->with('success', 'Role deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting role: ' . $e->getMessage());
        }
    }
}
