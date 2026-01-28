@extends('layouts.admin')

@section('content')

<h2 class="mb-4">Add Category</h2>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('categories.store') }}" method="POST">
@csrf

<div class="mb-3">
    <label class="form-label">Category Name</label>
    <input type="text" name="category_name" value="{{ old('category_name') }}" class="form-control">
</div>

<button class="btn btn-success">Save</button>
<a href="{{ route('categories.index') }}" class="btn btn-secondary">Back</a>

</form>

@endsection
