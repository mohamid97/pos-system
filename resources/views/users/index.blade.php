@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h4"><i class="fas fa-users me-2"></i>Users Management</h1>
            @can('create_user')
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Add New User
                </a>
            @endcan

        </div>



<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('users.index') }}" method="GET">
            <div class="row g-2">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search by name or email" 
                               value="{{ request('search') }}">
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="fas fa-search"></i>
                        </button>

                    </div>
                </div>


                <div class="col-md-3">
                        <select class="form-select" name="role">
                            <option value="">All Roles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                </div>



                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"> <i class="fas fa-filter me-1"></i> Filter</button>
                  
                        @if(request('search') || request('role') || request('status'))
                          <div class="mt-2 d-flex justify-content-center">
                                <a href="{{ route('products.index') }}" class="btn btn-outline-danger">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        @endif

                </div>

            </div>


        </form>
    </div>
</div>



        <div class="card">
            <div class="card-body p-0" >
                <!-- Users Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->roles->isNotEmpty())
                                            @foreach($user->roles as $role)
                                                <span class="badge bg-info">{{ $role->name }}</span>
                                            @endforeach
                                        @else
                                            <span class="badge bg-secondary">No Role</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    <td class="text-end">
                                        @can('edit_user')
                                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan

                                        @can('delete_user')
                                                                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endcan

                                    </td>
                               
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>
</div>



@if($users->hasPages())
<div class="mt-3">
    <x-pagination :paginator="$users" :showInfo="true" />
</div>
@endif



@endsection