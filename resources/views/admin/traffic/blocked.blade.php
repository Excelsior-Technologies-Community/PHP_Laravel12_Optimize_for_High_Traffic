@extends('layouts.admin')

@section('title', 'Blocked Requests')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                <i class="bi bi-shield-x me-2"></i>
                Blocked Requests
            </h2>

            <p class="text-muted mb-0">
                Requests blocked by the traffic protection system.
            </p>

        </div>

        <a href="{{ route('traffic.dashboard') }}"
           class="btn btn-outline-primary">

            <i class="bi bi-arrow-left me-1"></i>
            Traffic Dashboard

        </a>

    </div>


    <div class="card shadow-sm border-0">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>
                            <th>IP Address</th>
                            <th>Method</th>
                            <th>Route</th>
                            <th>User Type</th>
                            <th>Limit</th>
                            <th>Retry After</th>
                            <th>Time</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($blockedRequests as $request)

                            <tr>

                                <td>
                                    {{ $request->id }}
                                </td>

                                <td>
                                    <code>
                                        {{ $request->ip_address }}
                                    </code>
                                </td>

                                <td>

                                    <span class="badge bg-secondary">
                                        {{ $request->method }}
                                    </span>

                                </td>

                                <td>

                                    <code>
                                        {{ $request->route ?? 'N/A' }}
                                    </code>

                                </td>

                                <td>

                                    @if($request->user_type === 'admin')

                                        <span class="badge bg-warning text-dark">
                                            Admin
                                        </span>

                                    @elseif($request->user_type === 'customer')

                                        <span class="badge bg-success">
                                            Customer
                                        </span>

                                    @else

                                        <span class="badge bg-primary">
                                            Public
                                        </span>

                                    @endif

                                </td>

                                <td>
                                    {{ $request->limit ?? 'N/A' }}/min
                                </td>

                                <td>
                                    {{ $request->retry_after ?? 0 }} sec
                                </td>

                                <td>
                                    {{ $request->created_at->format('d M Y H:i:s') }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8"
                                    class="text-center py-5 text-muted">

                                    <i class="bi bi-shield-check fs-1"></i>

                                    <p class="mt-2 mb-0">
                                        No blocked requests found.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($blockedRequests->hasPages())

            <div class="card-footer bg-white">

                {{ $blockedRequests->links() }}

            </div>

        @endif

    </div>

</div>

@endsection