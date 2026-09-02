@extends('layouts.admin')

@section('title', 'Traffic Monitoring')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-speedometer2 me-2"></i>
                Traffic Monitoring
            </h2>

            <p class="text-muted mb-0">
                Monitor application traffic, performance and rate limits.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('traffic.blocked') }}"
               class="btn btn-outline-danger">

                <i class="bi bi-shield-x me-1"></i>
                Blocked Requests
            </a>

            <form action="{{ route('traffic.clear') }}"
                  method="POST"
                  onsubmit="return confirm('Clear all traffic statistics?')">

                @csrf
                @method('DELETE')

                <button type="submit"
                        class="btn btn-outline-secondary">

                    <i class="bi bi-trash me-1"></i>
                    Clear Statistics

                </button>

            </form>

        </div>

    </div>

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif

    {{-- Statistics --}}

    <div class="row g-4 mb-4">

        <div class="col-md-6 col-xl-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <p class="text-muted mb-1">
                                Requests Today
                            </p>

                            <h3 class="fw-bold mb-0">
                                {{ number_format($requestsToday) }}
                            </h3>

                        </div>

                        <div class="text-primary fs-2">
                            <i class="bi bi-bar-chart"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-6 col-xl-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <p class="text-muted mb-1">
                                Requests / Minute
                            </p>

                            <h3 class="fw-bold mb-0">
                                {{ number_format($requestsPerMinute) }}
                            </h3>

                        </div>

                        <div class="text-success fs-2">
                            <i class="bi bi-lightning-charge"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-6 col-xl-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <p class="text-muted mb-1">
                                Blocked Today
                            </p>

                            <h3 class="fw-bold mb-0 text-danger">
                                {{ number_format($blockedToday) }}
                            </h3>

                        </div>

                        <div class="text-danger fs-2">
                            <i class="bi bi-shield-x"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-6 col-xl-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <p class="text-muted mb-1">
                                Avg Response
                            </p>

                            <h3 class="fw-bold mb-0">
                                {{ number_format($averageResponseTime, 2) }} ms
                            </h3>

                        </div>

                        <div class="text-warning fs-2">
                            <i class="bi bi-stopwatch"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Traffic Breakdown --}}

    <div class="row g-4 mb-4">

        <div class="col-lg-4">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        <i class="bi bi-people me-2"></i>
                        Traffic Breakdown
                    </h5>

                </div>

                <div class="card-body">

                    <div class="mb-4">

                        <div class="d-flex justify-content-between mb-1">

                            <span>Public</span>

                            <strong>
                                {{ number_format($publicRequests) }}
                            </strong>

                        </div>

                        <div class="progress">

                            <div class="progress-bar"
                                 style="width: {{ $requestsToday > 0 ? ($publicRequests / $requestsToday) * 100 : 0 }}%">
                            </div>

                        </div>

                    </div>


                    <div class="mb-4">

                        <div class="d-flex justify-content-between mb-1">

                            <span>Customers</span>

                            <strong>
                                {{ number_format($customerRequests) }}
                            </strong>

                        </div>

                        <div class="progress">

                            <div class="progress-bar bg-success"
                                 style="width: {{ $requestsToday > 0 ? ($customerRequests / $requestsToday) * 100 : 0 }}%">
                            </div>

                        </div>

                    </div>


                    <div>

                        <div class="d-flex justify-content-between mb-1">

                            <span>Admin</span>

                            <strong>
                                {{ number_format($adminRequests) }}
                            </strong>

                        </div>

                        <div class="progress">

                            <div class="progress-bar bg-warning"
                                 style="width: {{ $requestsToday > 0 ? ($adminRequests / $requestsToday) * 100 : 0 }}%">
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-lg-8">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-white">

                    <div class="d-flex justify-content-between">

                        <h5 class="mb-0">
                            <i class="bi bi-graph-up me-2"></i>
                            Traffic Activity
                        </h5>

                        <span class="badge bg-primary">
                            Peak:
                            {{ number_format($peakRequestsPerMinute) }}/min
                        </span>

                    </div>

                </div>

                <div class="card-body">

                    <canvas id="trafficChart"
                            height="120">
                    </canvas>

                </div>

            </div>

        </div>

    </div>


    {{-- Rate Limits --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                <i class="bi bi-shield-check me-2"></i>
                Active Rate Limits
            </h5>

        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-2">

                    <div class="border rounded p-3">

                        <small class="text-muted">
                            Public
                        </small>

                        <h4 class="mb-0">
                            {{ config('traffic.limits.public') }}/min
                        </h4>

                    </div>

                </div>


                <div class="col-md-2">

                    <div class="border rounded p-3">

                        <small class="text-muted">
                            Customer
                        </small>

                        <h4 class="mb-0">
                            {{ config('traffic.limits.customer') }}/min
                        </h4>

                    </div>

                </div>


                <div class="col-md-2">

                    <div class="border rounded p-3">

                        <small class="text-muted">
                            Admin
                        </small>

                        <h4 class="mb-0">
                            {{ config('traffic.limits.admin') }}/min
                        </h4>

                    </div>

                </div>


                <div class="col-md-2">

                    <div class="border rounded p-3">

                        <small class="text-muted">
                            Authentication
                        </small>

                        <h4 class="mb-0">
                            {{ config('traffic.limits.auth') }}/min
                        </h4>

                    </div>

                </div>


                <div class="col-md-2">

                    <div class="border rounded p-3">

                        <small class="text-muted">
                            Checkout
                        </small>

                        <h4 class="mb-0">
                            {{ config('traffic.limits.checkout') }}/min
                        </h4>

                    </div>

                </div>


                <div class="col-md-2">

                    <div class="border rounded p-3">

                        <small class="text-muted">
                            Payment
                        </small>

                        <h4 class="mb-0">
                            {{ config('traffic.limits.payment') }}/min
                        </h4>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Blocked Routes --}}

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Frequently Blocked Routes
            </h5>

        </div>

        <div class="card-body p-0">

            @if($topRoutes->count())

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead>

                            <tr>
                                <th>Route</th>
                                <th>Blocked Requests</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($topRoutes as $route)

                                <tr>

                                    <td>
                                        <code>
                                            {{ $route->route }}
                                        </code>
                                    </td>

                                    <td>
                                        <span class="badge bg-danger">
                                            {{ number_format($route->blocked_count) }}
                                        </span>
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5 text-muted">

                    <i class="bi bi-shield-check fs-1"></i>

                    <p class="mt-2 mb-0">
                        No blocked requests today.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const chartData = @json($chartStats);

const labels = chartData.map(item => {

    const value = item.minute.toString().padStart(4, '0');

    return value.substring(0, 2) + ':' + value.substring(2, 4);

});

const requests = chartData.map(
    item => item.requests
);

const blocked = chartData.map(
    item => item.blocked
);

new Chart(
    document.getElementById('trafficChart'),
    {
        type: 'line',

        data: {
            labels: labels,

            datasets: [
                {
                    label: 'Requests',
                    data: requests,
                    tension: 0.3,
                    fill: false
                },
                {
                    label: 'Blocked',
                    data: blocked,
                    tension: 0.3,
                    fill: false
                }
            ]
        },

        options: {
            responsive: true,

            interaction: {
                intersect: false,
                mode: 'index'
            },

            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    }
);

</script>

@endpush