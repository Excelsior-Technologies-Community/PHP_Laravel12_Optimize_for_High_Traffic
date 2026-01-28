@extends('layouts.admin')

@section('content')

<h2 class="mb-4">Edit Product</h2>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('products.update',$product->id) }}" method="POST" enctype="multipart/form-data">
@csrf
@method('PUT')

<div class="mb-3">
    <label class="form-label">Product Name</label>
    <input type="text" name="name"
           value="{{ old('name', $product->name) }}"
           class="form-control">
</div>

<div class="mb-3">
    <label class="form-label">Details</label>
    <textarea name="details" class="form-control">{{ old('details', $product->details) }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">Price</label>
    <input type="number" name="price"
           value="{{ old('price', $product->price) }}"
           class="form-control">
</div>

{{-- ✅ Sizes (ID based) --}}
<div class="mb-3">
    <label class="form-label">Sizes</label>
    <select name="sizes[]" class="form-control select2" multiple>
        @foreach($sizes as $size)
            <option value="{{ $size->id }}"
                {{ in_array($size->id, old('sizes', $product->sizes ?? [])) ? 'selected' : '' }}>
                {{ $size->size_name }}
            </option>
        @endforeach
    </select>
</div>

{{-- ✅ Colors (ID based) --}}
<div class="mb-3">
    <label class="form-label">Colors</label>
    <select name="colors[]" class="form-control select2" multiple>
        @foreach($colors as $color)
            <option value="{{ $color->id }}"
                {{ in_array($color->id, old('colors', $product->colors ?? [])) ? 'selected' : '' }}>
                {{ $color->color_name }}
            </option>
        @endforeach
    </select>
</div>

{{-- ✅ Categories (ID based) --}}
<div class="mb-3">
    <label class="form-label">Categories</label>
    <select name="categories[]" class="form-control select2" multiple>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}"
                {{ in_array($cat->id, old('categories', $product->categories ?? [])) ? 'selected' : '' }}>
                {{ $cat->category_name }}
            </option>
        @endforeach
    </select>
</div>

{{-- Image --}}
<div class="mb-3">
    <label class="form-label">Current Image</label><br>
    <img src="{{ asset('images/'.$product->image) }}" width="120" class="rounded mb-2">
    <input type="file" name="image" class="form-control mt-2" onchange="previewNewImage(this)">
    <img id="newPreview" class="mt-2 rounded d-none" width="120">
</div>

<button class="btn btn-primary">Update Product</button>
<a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>

</form>

<script>
function previewNewImage(input) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = e => {
            document.getElementById('newPreview').src = e.target.result;
            document.getElementById('newPreview').classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

@endsection
