@extends('layouts.admin')

@section('content')

<h2 class="mb-4">Add Product Variant</h2>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('product-variants.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label class="form-label">Product</label>
        <select name="product_id" class="form-control">
            <option value="">Select Product</option>
            @foreach($products as $product)
            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                {{ $product->name }}
            </option>
            @endforeach
        </select>
        @error('product_id')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">SKU</label>
        <input type="text" name="sku" value="{{ old('sku') }}" class="form-control">
        @error('sku')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Size</label>
        <select name="size_id" class="form-control">
            <option value="">Select Size</option>
            @foreach($sizes as $size)
            <option value="{{ $size->id }}" {{ old('size_id') == $size->id ? 'selected' : '' }}>
                {{ $size->size_name }}
            </option>
            @endforeach
        </select>
        @error('size_id')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Color</label>
        <select name="color_id" class="form-control">
            <option value="">Select Color</option>
            @foreach($colors as $color)
            <option value="{{ $color->id }}" {{ old('color_id') == $color->id ? 'selected' : '' }}>
                {{ $color->color_name }}
            </option>
            @endforeach
        </select>
        @error('color_id')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-control">
            <option value="">Select Category</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->category_name }}
            </option>
            @endforeach
        </select>
        @error('category_id')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Price</label>
        <input type="number" name="price" value="{{ old('price') }}" class="form-control">
        @error('price')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Stock</label>
        <input type="number" name="stock" value="{{ old('stock') }}" class="form-control">
        @error('stock')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Image — Upload File</label>
        <input type="file" name="image" class="form-control" accept="image/*">
        <small class="text-muted">OR paste an online image URL below</small>
        <input type="text" name="image_url" value="{{ old('image_url') }}" class="form-control mt-2"
               placeholder="https://placehold.co/400x400?text=Variant">
        @error('image')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <button class="btn btn-success">Save Variant</button>
    <a href="{{ route('product-variants.index') }}" class="btn btn-secondary">Back</a>
</form>

@endsection
