@extends('layouts.app')

@section('title', 'Add Product')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h4"><i class="fas fa-plus me-2"></i>Add Product</h1>
            @can('view_product')
                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back
                </a>
            @endcan

        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label fw-bold">Product Name</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label fw-bold">Category</label>
                            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="sku" class="form-label fw-bold">SKU (Optional)</label>
                            <input type="text" name="sku" id="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku') }}">
                            <small class="form-text text-muted">Leave blank to auto-generate.</small>
                            @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="barcode" class="form-label fw-bold">Barcode (Optional)</label>
                            <input type="text" name="barcode" id="barcode" class="form-control @error('barcode') is-invalid @enderror" value="{{ old('barcode') }}">
                            @error('barcode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label fw-bold">Price</label>
                            <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" step="0.01" min="0" required>
                            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                       <div class="col-md-6 mb-3">
                            <label for="cost_price" class="form-label fw-bold">Cost Price</label>
                            <input type="number" name="cost_price" id="cost_price" class="form-control @error('cost_price') is-invalid @enderror" value="{{ old('cost_price') }}" step="0.01" min="0" required>
                            @error('cost_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="stock" class="form-label fw-bold">Stock</label>
                            <input type="number" name="stock_quantity" id="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock') }}" min="0" required>
                            @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>


                        <div class="col-md-6 mb-3">
                            <label for="min_stock_level" class="form-label fw-bold">Min. Stock Level</label>
                            <input type="number" name="min_stock_level" id="min_stock_level" class="form-control @error('min_stock_level') is-invalid @enderror" value="{{ old('min_stock_level', 0) }}" min="0" required>
                            @error('min_stock_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label fw-bold">Product Image</label>
                        <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" checked>
                        <label class="form-check-label" for="is_active">Product is Active</label>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i>Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection