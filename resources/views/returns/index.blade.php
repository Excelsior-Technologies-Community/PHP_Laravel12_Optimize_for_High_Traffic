@extends('layouts.customer')

@section('content')

<h3 class="mb-4 fw-bold">Return Requests</h3>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

{{-- Filter --}}
<form method="GET" action="{{ route('returns.index') }}" class="row g-2 mb-4">
    <div class="col-md-4">
        <select name="status" class="form-select">
            <option value="">All Status</option>
            <option value="pending"   {{ request('status')=='pending'   ? 'selected' : '' }}>Pending</option>
            <option value="approved"  {{ request('status')=='approved'  ? 'selected' : '' }}>Approved</option>
            <option value="rejected"  {{ request('status')=='rejected'  ? 'selected' : '' }}>Rejected</option>
            <option value="processed" {{ request('status')=='processed' ? 'selected' : '' }}>Processed</option>
        </select>
    </div>
    <div class="col-md-3">
        <button class="btn btn-primary">Filter</button>
        <a href="{{ route('returns.index') }}" class="btn btn-outline-secondary">Reset</a>
    </div>
</form>

<div class="d-flex justify-content-end mb-3">
    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#returnModal">
        + Create Return Request
    </button>
</div>

<table class="table table-bordered table-hover align-middle">
    <thead class="table-dark">
        <tr>
            <th>Order ID</th>
            <th>Product</th>
            <th>Qty</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
    @forelse($returnRequests as $ret)
        <tr>
            <td>#{{ $ret->order_id }}</td>
            <td>{{ $ret->orderItem->product->name ?? 'Unknown' }}</td>
            <td>{{ $ret->orderItem->quantity ?? '-' }}</td>
            <td>{{ Str::limit($ret->reason, 60) }}</td>
            <td>
                @php $colors = ['pending'=>'warning','approved'=>'success','rejected'=>'danger','processed'=>'info']; @endphp
                <span class="badge bg-{{ $colors[$ret->status] ?? 'dark' }}">
                    {{ ucfirst($ret->status) }}
                </span>
            </td>
            <td>{{ $ret->created_at->format('d M Y') }}</td>
        </tr>
    @empty
        <tr><td colspan="6" class="text-center text-muted">No return requests found</td></tr>
    @endforelse
    </tbody>
</table>

{{ $returnRequests->links('pagination::bootstrap-5') }}

{{-- CREATE MODAL --}}
<div class="modal fade" id="returnModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Return Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('returns.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Order</label>
                        <select name="order_id" id="orderSelect" class="form-select" required>
                            <option value="">-- Select Order --</option>
                            @foreach($orders as $order)
                                <option value="{{ $order->id }}">
                                    Order #{{ $order->id }} — {{ $order->created_at->format('d M Y') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select Product</label>
                        <select name="order_item_id" id="itemSelect" class="form-select" required>
                            <option value="">-- Select Order First --</option>
                            @foreach($orderItems as $item)
                                <option value="{{ $item->id }}" data-order="{{ $item->order_id }}" style="display:none">
                                    {{ $item->product->name ?? 'Unknown' }} (Qty: {{ $item->quantity }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason (min 10 chars)</label>
                        <textarea name="reason" class="form-control" rows="3" required minlength="10"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('orderSelect').addEventListener('change', function () {
    const orderId = this.value;
    const options = document.querySelectorAll('#itemSelect option[data-order]');
    options.forEach(opt => {
        opt.style.display = (opt.dataset.order == orderId) ? '' : 'none';
    });
    document.getElementById('itemSelect').value = '';
});
</script>

@endsection
