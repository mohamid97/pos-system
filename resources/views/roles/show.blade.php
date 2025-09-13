@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Role Details: {{ $role->name }}</h5>
                    <div>
                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning me-2">
                            <i class="fas fa-edit"></i> Edit Role
                        </a>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Roles
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Role Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header py-2">
                                    <h6 class="mb-0">Role Information</h6>
                                </div>
                                <div class="card-body">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-4">Name:</dt>
                                        <dd class="col-sm-8">
                                            <span class="badge bg-primary fs-6">{{ $role->name }}</span>
                                        </dd>
                                        
                                        <dt class="col-sm-4">Created:</dt>
                                        <dd class="col-sm-8">{{ $role->created_at->format('M d, Y g:i A') }}</dd>
                                        
                                        <dt class="col-sm-4">Updated:</dt>
                                        <dd class="col-sm-8">{{ $role->updated_at->format('M d, Y g:i A') }}</dd>
                                        
                                        <dt class="col-sm-4">Permissions:</dt>
                                        <dd class="col-sm-8">
                                            <span class="badge bg-info">{{ $role->permissions->count() }} permissions</span>
                                        </dd>

                                        @if($role->users)
                                            <dt class="col-sm-4">Users:</dt>
                                            <dd class="col-sm-8">
                                                <span class="badge bg-success">{{ $role->users->count() }} users</span>
                                            </dd>
                                        @endif
                                    </dl>
                                </div>
                            </div>
                        </div>

                        <!-- Role Status -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header py-2">
                                    <h6 class="mb-0">Role Status</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        @if($role->name === 'admin')
                                            <span class="badge bg-danger fs-6">
                                                <i class="fas fa-crown"></i> System Administrator
                                            </span>
                                        @elseif($role->permissions->count() > 10)
                                            <span class="badge bg-warning fs-6">
                                                <i class="fas fa-user-shield"></i> High Privilege
                                            </span>
                                        @elseif($role->permissions->count() > 5)
                                            <span class="badge bg-info fs-6">
                                                <i class="fas fa-user-check"></i> Moderate Privilege
                                            </span>
                                        @else
                                            <span class="badge bg-secondary fs-6">
                                                <i class="fas fa-user"></i> Basic Role
                                            </span>
                                        @endif
                                    </div>

                                    @if($role->permissions->isEmpty())
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            This role has no permissions assigned.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Permissions Display -->
                    @if($role->permissions->isNotEmpty())
                        <div class="mb-4">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center py-2">
                                    <h6 class="mb-0">Assigned Permissions ({{ $role->permissions->count() }})</h6>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="toggle-permissions">
                                        <i class="fas fa-eye"></i> Toggle View
                                    </button>
                                </div>
                                <div class="card-body" id="permissions-container">
                                    <div class="row" id="permissions-grid">
                                        @php
                                            $groupedPermissions = $role->permissions->groupBy(function($permission) {
                                                return explode('_', $permission->name)[1] ?? 'other';
                                            });
                                        @endphp

                                        @foreach($groupedPermissions as $group => $perms)
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card border-left-primary">
                                                    <div class="card-header py-2 bg-light">
                                                        <h6 class="mb-0 text-capitalize text-primary">
                                                            <i class="fas fa-folder"></i> {{ $group }}
                                                            <span class="badge bg-primary ms-1">{{ $perms->count() }}</span>
                                                        </h6>
                                                    </div>
                                                    <div class="card-body py-2">
                                                        @foreach($perms as $permission)
                                                            <div class="mb-1">
                                                                <span class="badge bg-success">
                                                                    <i class="fas fa-check"></i>
                                                                    {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                                                </span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Alternative List View -->
                                    <div class="d-none" id="permissions-list">
                                        <div class="row">
                                            @foreach($role->permissions->chunk(ceil($role->permissions->count()/3)) as $chunk)
                                                <div class="col-md-4">
                                                    @foreach($chunk as $permission)
                                                        <div class="mb-2">
                                                            <span class="badge bg-primary">
                                                                <i class="fas fa-check-circle"></i>
                                                                {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Users with this Role (if relationship exists) -->
                    @if($role->users && $role->users->isNotEmpty())
                        <div class="mb-4">
                            <div class="card">
                                <div class="card-header py-2">
                                    <h6 class="mb-0">Users with this Role ({{ $role->users->count() }})</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($role->users->take(12) as $user)
                                            <div class="col-md-6 col-lg-4 mb-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-2">
                                                        <div class="avatar-title bg-light text-primary rounded-circle">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="font-size-sm font-weight-bold">{{ $user->name }}</div>
                                                        <div class="text-muted font-size-xs">{{ $user->email }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    @if($role->users->count() > 12)
                                        <div class="mt-3">
                                            <small class="text-muted">
                                                And {{ $role->users->count() - 12 }} more users...
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-arrow-left"></i> Back to Roles
                        </a>
                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning me-md-2">
                            <i class="fas fa-edit"></i> Edit Role
                        </a>
                        @if($role->name !== 'admin')
                            <form method="POST" action="{{ route('roles.destroy', $role->id) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this role?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Delete Role
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Sidebar -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header py-2">
                    <h6 class="mb-0">Quick Stats</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-12 mb-3">
                            <div class="border-bottom pb-2">
                                <h4 class="text-primary">{{ $role->permissions->count() }}</h4>
                                <small class="text-muted">Total Permissions</small>
                            </div>
                        </div>
                        
                        @if($role->users)
                            <div class="col-12 mb-3">
                                <div class="border-bottom pb-2">
                                    <h4 class="text-success">{{ $role->users->count() }}</h4>
                                    <small class="text-muted">Assigned Users</small>
                                </div>
                            </div>
                        @endif

                        <div class="col-12 mb-3">
                            <div class="pb-2">
                                <h4 class="text-info">{{ $role->created_at->diffForHumans() }}</h4>
                                <small class="text-muted">Created</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permission Categories -->
            @if($role->permissions->isNotEmpty())
                <div class="card mt-3">
                    <div class="card-header py-2">
                        <h6 class="mb-0">Permission Categories</h6>
                    </div>
                    <div class="card-body">
                        @php
                            $categories = $role->permissions->groupBy(function($permission) {
                                return explode('_', $permission->name)[1] ?? 'other';
                            });
                        @endphp
                        
                        @foreach($categories as $category => $perms)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-capitalize">{{ $category }}</span>
                                <span class="badge bg-primary">{{ $perms->count() }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.avatar-sm {
    width: 2rem;
    height: 2rem;
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    font-size: 0.875rem;
    font-weight: 600;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggle-permissions');
    const gridView = document.getElementById('permissions-grid');
    const listView = document.getElementById('permissions-list');
    
    if (toggleBtn && gridView && listView) {
        let isGridView = true;
        
        toggleBtn.addEventListener('click', function() {
            if (isGridView) {
                gridView.classList.add('d-none');
                listView.classList.remove('d-none');
                toggleBtn.innerHTML = '<i class="fas fa-th"></i> Grid View';
                isGridView = false;
            } else {
                gridView.classList.remove('d-none');
                listView.classList.add('d-none');
                toggleBtn.innerHTML = '<i class="fas fa-list"></i> List View';
                isGridView = true;
            }
        });
    }
});
</script>
@endsection