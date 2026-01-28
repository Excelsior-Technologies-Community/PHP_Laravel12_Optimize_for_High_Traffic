@extends('layouts.admin')

@section('content')

<h2 class="mb-4">Add Product</h2>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="mb-3">
    <label class="form-label">Product Name</label>
    <input type="text" name="name" value="{{ old('name') }}" class="form-control">
</div>

<div class="mb-3">
    <label class="form-label">Details</label>
    <textarea name="details" class="form-control">{{ old('details') }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">Price</label>
    <input type="number" name="price" value="{{ old('price') }}" class="form-control">
</div>

{{-- ✅ Sizes (ID save hogi) --}}
<div class="mb-3">
    <label class="form-label">Sizes</label>
    <select name="sizes[]" class="form-control select2" multiple>
        @foreach($sizes as $size)
            <option value="{{ $size->id }}"
                {{ collect(old('sizes'))->contains($size->id) ? 'selected' : '' }}>
                {{ $size->size_name }}
            </option>
        @endforeach
    </select>
</div>

{{-- ✅ Colors (ID save hogi) --}}
<div class="mb-3">
    <label class="form-label">Colors</label>
    <select name="colors[]" class="form-control select2" multiple>
        @foreach($colors as $color)
            <option value="{{ $color->id }}"
                {{ collect(old('colors'))->contains($color->id) ? 'selected' : '' }}>
                {{ $color->color_name }}
            </option>
        @endforeach
    </select>
</div>

{{-- ✅ Categories (ID save hogi) --}}
<div class="mb-3">
    <label class="form-label">Categories</label>
    <select name="categories[]" class="form-control select2" multiple>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}"
                {{ collect(old('categories'))->contains($cat->id) ? 'selected' : '' }}>
                {{ $cat->category_name }}
            </option>
        @endforeach
    </select>
</div>

{{-- Image Upload --}}
<div class="mb-3">
    <label class="form-label">Image</label>
    <input type="file" name="image" class="form-control" onchange="previewImage(this)">
    <img id="preview" class="mt-2 rounded d-none" width="120">
</div>

<button class="btn btn-success">Save Product</button>
<a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>

</form>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = e => {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('preview').classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

@endsection
