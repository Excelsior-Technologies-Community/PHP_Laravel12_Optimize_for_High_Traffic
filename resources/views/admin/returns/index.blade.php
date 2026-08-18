@extends('layouts.admin')

@section('content')

<h2 class="mb-3">Return List</h2>

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

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Product Name</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Date</th>
            <th width="150">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($returns as $return)
        <tr>
            <td>{{ $return->id }}</td>
            <td>{{ $return->order_id }}</td>
            <td>{{ $return->customer->name ?? 'Guest' }}</td>
            <td>{{ $return->orderItem->product->name ?? 'Deleted Product' }}</td>
            <td>{{ Str::limit($return->reason, 50) }}</td>
            <td>
                @if($return->status == 'approved')
                <span class="badge bg-success">Approved</span>
                @elseif($return->status == 'pending')
                <span class="badge bg-warning text-dark">Pending</span>
                @elseif($return->status == 'completed')
                <span class="badge bg-info">Completed</span>
                @else
                <span class="badge bg-danger">Rejected</span>
                @endif
            </td>
            <td>{{ $return->created_at->format('d M Y') }}</td>
            <td>
                <form action="{{ route('admin.returns.status', $return->id) }}" method="POST" class="d-inline">
                    @csrf
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Change Status</option>
                        <option value="approved" {{ $return->status == 'approved' ? 'selected' : '' }}>Approve</option>
                        <option value="pending" {{ $return->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ $return->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="processed" {{ $return->status == 'processed' ? 'selected' : '' }}>Processed</option>
                        <option value="rejected" {{ $return->status == 'rejected' ? 'selected' : '' }}>Reject</option>
                    </select>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center text-muted">No returns found</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-center mt-3">
    {{ $returns->links('pagination::bootstrap-5') }}
</div>

@endsection
