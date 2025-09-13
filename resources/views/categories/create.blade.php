@extends('layouts.app')

@section('title', 'Add Category')

@section('content')
<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h4"><i class="fas fa-plus me-2"></i>Add Category</h1>
            @can('view_category')
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back
                </a>
            @endcan

        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Category Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                  rows="3">{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label fw-bold">Category Image</label>
                        <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" checked>
                        <label class="form-check-label" for="is_active">Category is Active</label>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i>Save Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection