@extends('layouts.app')

@section('title', 'Roles')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4"><i class="fas fa-user-tag me-2"></i>Roles</h1>
    @can('create_role')
        <a href="{{ route('roles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add Role
        </a>
    @endcan
</div>

<!-- Filter Card -->
<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('roles.index') }}" method="GET">
            <div class="row g-2">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search roles..." 
                               value="{{ request('search') }}">
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="permission_count">
                        <option value="">All Permission Counts</option>
                        <option value="0" {{ request('permission_count') === '0' ? 'selected' : '' }}>No Permissions</option>
                        <option value="1-5" {{ request('permission_count') === '1-5' ? 'selected' : '' }}>1-5 Permissions</option>
                        <option value="6-10" {{ request('permission_count') === '6-10' ? 'selected' : '' }}>6-10 Permissions</option>
                        <option value="11+" {{ request('permission_count') === '11+' ? 'selected' : '' }}>11+ Permissions</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="has_users">
                        <option value="">All Roles</option>
                        <option value="1" {{ request('has_users') === '1' ? 'selected' : '' }}>With Users</option>
                        <option value="0" {{ request('has_users') === '0' ? 'selected' : '' }}>Without Users</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    
                    @if(request('search') || request('permission_count') || request('has_users'))
                        <div class="mt-2 d-flex justify-content-center">
                            <a href="{{ route('roles.index') }}" class="btn btn-outline-danger">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
{{-- 
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif --}}

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Permissions</th>
                        <th>Users Count</th>
                        <th>Created At</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="badge bg-secondary me-2">
                                        <i class="fas fa-user-tag"></i>
                                    </div>
                                    <span class="fw-medium">{{ $role->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $role->permissions->count() }} permissions</span>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $role->users->count() }} users</span>
                            </td>
                            <td>{{ $role->created_at->format('M d, Y') }}</td>
                            <td class="text-end">
                                @can('view_role')
                                    <a href="{{ route('roles.show', $role->id) }}" 
                                       class="btn btn-sm btn-outline-info"
                                       title="View Role">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @endcan

                                @can('edit_role')
                                    <a href="{{ route('roles.edit', $role->id) }}" 
                                       class="btn btn-sm btn-warning"
                                       title="Edit Role">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan

                                @can('delete_role')
                                    @if($role->name !== 'admin')
                                        <form method="POST" 
                                              action="{{ route('roles.destroy', $role->id) }}" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this role?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger"
                                                    title="Delete Role">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No roles found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($roles->hasPages())
<div class="mt-3">
    <x-pagination :paginator="$roles" :showInfo="true" />
</div>
@endif
@endsection