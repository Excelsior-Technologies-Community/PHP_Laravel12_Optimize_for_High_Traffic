@extends('layouts.admin')

@section('content')

<h2 class="mb-3">Review List</h2>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Filter --}}
<form method="GET" action="{{ route('admin.reviews.index') }}" class="row g-2 mb-3">
    <div class="col-md-3">
        <select name="status" class="form-select" onchange="this.form.submit()">
            <option value="">All Status</option>
            <option value="pending"  {{ request('status')=='pending'  ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('status')=='approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ request('status')=='rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
    </div>
    <div class="col-md-2">
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">Reset</a>
    </div>
</form>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Customer</th>
            <th>Rating</th>
            <th>Review</th>
            <th>Status</th>
            <th width="160">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($reviews as $review)
        <tr>
            <td>{{ $review->id }}</td>
            <td>{{ $review->product->name ?? 'Deleted Product' }}</td>
            <td>{{ $review->customer->name ?? 'Guest' }}</td>
            <td>
                @for($i = 1; $i <= 5; $i++)
                    <span class="{{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}">&#9733;</span>
                @endfor
                ({{ $review->rating }})
            </td>
            <td>{{ Str::limit($review->review, 60) }}</td>
            <td>
                @php $colors = ['approved'=>'success','pending'=>'warning','rejected'=>'danger']; @endphp
                <span class="badge bg-{{ $colors[$review->status] ?? 'secondary' }}">
                    {{ ucfirst($review->status) }}
                </span>
            </td>
            <td>
                <form action="{{ route('admin.reviews.status', $review->id) }}" method="POST" class="d-inline">
                    @csrf
                    <select name="status" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
                        <option value="approved" {{ $review->status=='approved' ? 'selected' : '' }}>Approve</option>
                        <option value="pending"  {{ $review->status=='pending'  ? 'selected' : '' }}>Pending</option>
                        <option value="rejected" {{ $review->status=='rejected' ? 'selected' : '' }}>Reject</option>
                    </select>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center text-muted">No reviews found</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-center mt-3">
    {{ $reviews->links('pagination::bootstrap-5') }}
</div>

@endsection
