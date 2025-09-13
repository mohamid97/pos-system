@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Create New Role</h5>
                    @can('view_role')
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    @endcan

                </div>

                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('roles.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="Enter role name"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Permissions</label>
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="select-all">
                                        Select All
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="deselect-all">
                                        Deselect All
                                    </button>
                                </div>
                            </div>
                            
                            <div class="row">
                                @php
                                    $groupedPermissions = $permissions->groupBy(function($permission) {
                                        return explode('_', $permission->name)[1] ?? 'other';
                                    });
                                @endphp

                                @foreach($groupedPermissions as $group => $perms)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card">
                                            <div class="card-header py-2">
                                                <h6 class="mb-0 text-capitalize">{{ $group }}</h6>
                                            </div>
                                            <div class="card-body py-2">
                                                @foreach($perms as $permission)
                                                    <div class="form-check">
                                                        <input class="form-check-input permission-checkbox" 
                                                               type="checkbox" 
                                                               name="permissions[]" 
                                                               value="{{ $permission->id }}" 
                                                               id="permission_{{ $permission->id }}"
                                                               {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                            {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('roles.index') }}" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Role</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllBtn = document.getElementById('select-all');
    const deselectAllBtn = document.getElementById('deselect-all');
    const checkboxes = document.querySelectorAll('.permission-checkbox');

    selectAllBtn.addEventListener('click', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
    });

    deselectAllBtn.addEventListener('click', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
    });
});
</script>
@endsection