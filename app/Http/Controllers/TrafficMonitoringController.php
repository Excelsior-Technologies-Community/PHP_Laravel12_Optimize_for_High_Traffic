<?php

namespace App\Http\Controllers;

use App\Models\TrafficBlockLog;
use App\Models\TrafficStatistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrafficMonitoringController extends Controller
{
    /**
     * Traffic dashboard.
     */
    public function index()
    {
        $today = now()->toDateString();

        $todayStats = TrafficStatistic::where(
            'stat_date',
            $today
        )->get();

        $requestsToday = $todayStats->sum('total_requests');

        $blockedToday = $todayStats->sum('blocked_requests');

        $totalResponseTime = $todayStats->sum(
            'total_response_time'
        );

        $averageResponseTime = $requestsToday > 0
            ? round($totalResponseTime / $requestsToday, 2)
            : 0;

        $currentMinute = (int) now()->format('Hi');

        $requestsPerMinute = TrafficStatistic::where(
            'stat_date',
            $today
        )
            ->where('stat_minute', $currentMinute)
            ->value('total_requests') ?? 0;

        $peakRequestsPerMinute = TrafficStatistic::where(
            'stat_date',
            $today
        )->max('total_requests') ?? 0;

        $publicRequests = $todayStats->sum(
            'public_requests'
        );

        $customerRequests = $todayStats->sum(
            'customer_requests'
        );

        $adminRequests = $todayStats->sum(
            'admin_requests'
        );

        $topRoutes = TrafficBlockLog::select(
            'route',
            DB::raw('COUNT(*) as blocked_count')
        )
            ->whereDate('created_at', today())
            ->whereNotNull('route')
            ->groupBy('route')
            ->orderByDesc('blocked_count')
            ->limit(10)
            ->get();

        $recentBlocked = TrafficBlockLog::latest()
            ->limit(20)
            ->get();

        $chartStats = TrafficStatistic::where(
            'stat_date',
            $today
        )
            ->orderBy('stat_minute')
            ->get()
            ->map(function ($stat) {
                return [
                    'minute' => str_pad(
                        $stat->stat_minute,
                        4,
                        '0',
                        STR_PAD_LEFT
                    ),
                    'requests' => $stat->total_requests,
                    'blocked' => $stat->blocked_requests,
                ];
            });

        return view('admin.traffic.index', compact(
            'requestsToday',
            'blockedToday',
            'averageResponseTime',
            'requestsPerMinute',
            'peakRequestsPerMinute',
            'publicRequests',
            'customerRequests',
            'adminRequests',
            'topRoutes',
            'recentBlocked',
            'chartStats'
        ));
    }

    /**
     * Show blocked requests.
     */
    public function blocked()
    {
        $blockedRequests = TrafficBlockLog::latest()
            ->paginate(25);

        return view(
            'admin.traffic.blocked',
            compact('blockedRequests')
        );
    }

    /**
     * Clear traffic statistics.
     */
    public function clear()
    {
        TrafficStatistic::query()->delete();
        TrafficBlockLog::query()->delete();

        return redirect()
            ->route('traffic.dashboard')
            ->with(
                'success',
                'Traffic monitoring statistics cleared successfully.'
            );
    }
}