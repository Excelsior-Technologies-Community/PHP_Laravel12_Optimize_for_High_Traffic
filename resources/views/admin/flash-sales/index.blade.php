@extends('layouts.admin')

@section('content')

<h2 class="mb-3">Flash Sale List</h2>

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

<a href="{{ route('flash-sales.create') }}" class="btn btn-primary mb-3">Add Flash Sale</a>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Product/Variant</th>
            <th>Discount Type</th>
            <th>Discount Value</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Stock</th>
            <th>Sold</th>
            <th>Status</th>
            <th width="150">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($flashSales as $flashSale)
        <tr>
            <td>{{ $flashSale->id }}</td>
            <td>
                @if($flashSale->product)
                {{ $flashSale->product->name }}
                @elseif($flashSale->productVariant)
                {{ $flashSale->productVariant->product->name ?? 'N/A' }} ({{ $flashSale->productVariant->sku ?? '' }})
                @else
                N/A
                @endif
            </td>
            <td>{{ ucfirst($flashSale->discount_type) }}</td>
            <td>{{ $flashSale->discount_value }}</td>
            <td>{{ $flashSale->start_date }}</td>
            <td>{{ $flashSale->end_date }}</td>
            <td>{{ $flashSale->stock }}</td>
            <td>{{ $flashSale->sold }}</td>
            <td>
                @if($flashSale->status == 'active')
                <span class="badge bg-success">Active</span>
                @else
                <span class="badge bg-danger">Inactive</span>
                @endif
            </td>
            <td>
                <a href="{{ route('flash-sales.edit', $flashSale->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('flash-sales.destroy', $flashSale->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="10" class="text-center text-muted">No flash sales found</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-center mt-3">
    {{ $flashSales->links('pagination::bootstrap-5') }}
</div>

@endsection
