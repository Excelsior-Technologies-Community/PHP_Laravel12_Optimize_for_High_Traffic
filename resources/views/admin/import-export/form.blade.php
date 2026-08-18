@extends('layouts.admin')

@section('content')

<h2 class="mb-4">Import / Export Products</h2>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<div class="row g-3">
    <div class="col-md-6">
        <div class="card card-body">
            <h5 class="mb-3">Import Products</h5>
            <form action="{{ route('import.execute') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Upload CSV File</label>
                    <input type="file" name="file" accept=".csv" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Upload CSV</button>
            </form>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card card-body">
            <h5 class="mb-3">Export Products</h5>
            <p class="text-muted">Download all products as CSV file.</p>
            <a href="{{ route('export.execute') }}" class="btn btn-info">Download CSV</a>
        </div>
    </div>
</div>

<div class="card card-body mt-4">
    <h5 class="mb-3">Instructions</h5>
    <p>Upload CSV with columns: name, details, price, image, sizes, colors, categories, status, stock, brand_id, sku</p>
    <ul>
        <li><strong>name</strong> - Product name</li>
        <li><strong>details</strong> - Product details</li>
        <li><strong>price</strong> - Product price</li>
        <li><strong>image</strong> - Image filename</li>
        <li><strong>sizes</strong> - Comma-separated size IDs</li>
        <li><strong>colors</strong> - Comma-separated color IDs</li>
        <li><strong>categories</strong> - Comma-separated category IDs</li>
        <li><strong>status</strong> - active or inactive</li>
        <li><strong>stock</strong> - Stock quantity</li>
        <li><strong>brand_id</strong> - Brand ID</li>
        <li><strong>sku</strong> - SKU code</li>
    </ul>
</div>

@endsection
