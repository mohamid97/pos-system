<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;


 class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $users = $this->userService->getAllUsers($request->all());
        $roles = $this->userService->getAllRoles();
        
        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $roles = $this->userService->getAllRoles();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage
     */
    public function store(UserRequest $request)
    {
        try {
            $this->userService->createUser($request->all());
            return redirect()->route('users.index')
                ->with('success', 'User created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating user: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified user
     */
    public function show($id)
    {
        $user = $this->userService->getUserById($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit($id)
    {
        $user = $this->userService->getUserById($id);
        $roles = $this->userService->getAllRoles();
        $userRoleId = $user->roles->first()?->id;

        return view('users.edit', compact('user', 'roles', 'userRoleId'));
    }

    /**
     * Update the specified user in storage
     */
    public function update(UserRequest $request, $id)
    {
        try {
            $this->userService->updateUser($id, $request->all());
            return redirect()->route('users.index')
                ->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating user: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy($id)
    {
        try {
            $this->userService->deleteUser($id);
            return redirect()->route('users.index')
                ->with('success', 'User deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }


}
