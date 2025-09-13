@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h4"><i class="fas fa-edit me-2"></i>Edit User</h1>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back
            </a>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('users.update', $user->id) }}" method="POST" autocomplete="off">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">Password</label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                               placeholder="Leave blank to keep current password">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label fw-bold">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                               placeholder="Confirm new password">
                    </div>
                    
                    <div class="mb-3">
                        <label for="role_id" class="form-label fw-bold">Role</label>
                        <select name="role_id" id="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id', $userRoleId) == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i>Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection