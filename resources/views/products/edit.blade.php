@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h4"><i class="fas fa-edit me-2"></i>Edit Product</h1>
            @can('view_product')
                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back
                </a>
            @endcan

        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">Product Name *</label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $product->name) }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sku" class="form-label fw-bold">SKU</label>
                                <input type="text" name="sku" id="sku" class="form-control @error('sku') is-invalid @enderror"
                                       value="{{ old('sku', $product->sku) }}">
                                @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label fw-bold">Category *</label>
                                <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="barcode" class="form-label fw-bold">Barcode</label>
                                <input type="text" name="barcode" id="barcode" class="form-control @error('barcode') is-invalid @enderror"
                                       value="{{ old('barcode', $product->barcode) }}">
                                @error('barcode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="price" class="form-label fw-bold">Price *</label>
                                <input type="number" step="0.01" name="price" id="price" class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price', $product->price) }}" required>
                                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="cost_price" class="form-label fw-bold">Cost Price *</label>
                                <input type="number" step="0.01" name="cost_price" id="cost_price" class="form-control @error('cost_price') is-invalid @enderror"
                                       value="{{ old('cost_price', $product->cost_price) }}" required>
                                @error('cost_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="stock_quantity" class="form-label fw-bold">Stock Quantity *</label>
                                <input type="number" name="stock_quantity" id="stock_quantity" class="form-control @error('stock_quantity') is-invalid @enderror"
                                       value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                                @error('stock_quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="min_stock_level" class="form-label fw-bold">Minimum Stock Level *</label>
                                <input type="number" name="min_stock_level" id="min_stock_level" class="form-control @error('min_stock_level') is-invalid @enderror"
                                       value="{{ old('min_stock_level', $product->min_stock_level) }}" required>
                                @error('min_stock_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="image" class="form-label fw-bold">Product Image</label>
                                <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                
                                @if($product->image)
                                    <div class="mt-2">
                                        <p class="mb-1">Current Image:</p>
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" class="img-thumbnail" width="150">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="remove_image" id="remove_image" value="1">
                                            <label class="form-check-label" for="remove_image">
                                                Remove current image
                                            </label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                  rows="4">{{ old('description', $product->description) }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" 
                            {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Product is Active</label>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i>Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection