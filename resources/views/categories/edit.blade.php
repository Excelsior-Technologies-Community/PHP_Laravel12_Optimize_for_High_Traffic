@extends('layouts.admin')

@section('content')

<h2 class="mb-4">Edit Category</h2>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('categories.update',$category->id) }}" method="POST">
@csrf
@method('PUT')

<div class="mb-3">
    <label class="form-label">Category Name</label>
    <input type="text" name="category_name"
           value="{{ old('category_name', $category->category_name) }}"
           class="form-control">
</div>

<button class="btn btn-primary">Update</button>
<a href="{{ route('categories.index') }}" class="btn btn-secondary">Back</a>

</form>

@endsection
