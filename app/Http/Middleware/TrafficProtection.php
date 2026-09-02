<?php

namespace App\Http\Middleware;

use App\Models\TrafficBlockLog;
use App\Models\TrafficStatistic;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class TrafficProtection
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!config('traffic.monitoring.enabled', true)) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();

        if (
            $routeName &&
            in_array($routeName, config('traffic.excluded_routes', []), true)
        ) {
            return $next($request);
        }

$limit = (int) $this->getLimit($request);
$key = $this->getRateLimitKey($request);
$decaySeconds = (int) config('traffic.decay_seconds', 60);

        if (RateLimiter::tooManyAttempts($key, $limit)) {
            $retryAfter = RateLimiter::availableIn($key);

            $this->recordBlockedRequest(
                $request,
                $limit,
                $retryAfter
            );

            return response()->json([
                'message' => 'Too many requests. Please try again later.',
                'retry_after' => $retryAfter,
            ], 429)
                ->header('X-RateLimit-Limit', $limit)
                ->header('X-RateLimit-Remaining', 0)
                ->header('Retry-After', $retryAfter);
        }

        RateLimiter::hit($key, $decaySeconds);

        $startTime = microtime(true);

        $response = $next($request);

        $responseTime = (int) round(
            (microtime(true) - $startTime) * 1000
        );

        $this->recordRequest(
            $request,
            $responseTime
        );

        $remaining = max(
            0,
            $limit - RateLimiter::attempts($key)
        );

        $response->headers->set(
            'X-RateLimit-Limit',
            $limit
        );

        $response->headers->set(
            'X-RateLimit-Remaining',
            $remaining
        );

        return $response;
    }

    /**
     * Determine the rate limit for the request.
     */
    protected function getLimit(Request $request): int
    {
        $route = $request->path();

        if (
            $request->is('login') ||
            $request->is('customer/login') ||
            $request->is('customer/register')
        ) {
            return config('traffic.limits.auth', 10);
        }

        if (
            str_starts_with($route, 'razorpay') ||
            str_contains($route, 'place-order')
        ) {
            return config('traffic.limits.payment', 30);
        }

        if (str_starts_with($route, 'checkout')) {
            return config('traffic.limits.checkout', 30);
        }

        if (
            str_starts_with($route, 'admin') ||
            $request->user()
        ) {
            return config('traffic.limits.admin', 300);
        }

        if (auth('customer')->check()) {
            return config('traffic.limits.customer', 180);
        }

        return (int) config(
    'traffic.default_limit',
    config('traffic.limits.public', 120)
);
    }

    /**
     * Generate a unique rate-limit key.
     */
    protected function getRateLimitKey(Request $request): string
    {
        $identity = $request->ip();

        if (auth('customer')->check()) {
            $identity = 'customer:' . auth('customer')->id();
        } elseif ($request->user()) {
            $identity = 'admin:' . $request->user()->id;
        }

        return 'traffic-rate-limit:' .
            sha1($identity . '|' . $request->path());
    }

    /**
     * Record successful request statistics.
     */
    protected function recordRequest(
        Request $request,
        int $responseTime
    ): void {
        if (!config('traffic.monitoring.database_logging', true)) {
            return;
        }

        $date = now()->toDateString();
        $minute = (int) now()->format('Hi');

        $type = $this->getTrafficType($request);

        $increments = [
            'total_requests' => 1,
            'total_response_time' => $responseTime,
            'max_response_time' => $responseTime,
        ];

        if ($type === 'admin') {
            $increments['admin_requests'] = 1;
        } elseif ($type === 'customer') {
            $increments['customer_requests'] = 1;
        } else {
            $increments['public_requests'] = 1;
        }

        $stat = TrafficStatistic::firstOrCreate(
            [
                'stat_date' => $date,
                'stat_minute' => $minute,
            ],
            [
                'total_requests' => 0,
                'blocked_requests' => 0,
                'public_requests' => 0,
                'customer_requests' => 0,
                'admin_requests' => 0,
                'total_response_time' => 0,
                'max_response_time' => 0,
            ]
        );

        $stat->increment(
            'total_requests',
            $increments['total_requests']
        );

        $stat->increment(
            'total_response_time',
            $responseTime
        );

        if ($responseTime > $stat->max_response_time) {
            $stat->update([
                'max_response_time' => $responseTime,
            ]);
        }

        if ($type === 'admin') {
            $stat->increment('admin_requests');
        } elseif ($type === 'customer') {
            $stat->increment('customer_requests');
        } else {
            $stat->increment('public_requests');
        }
    }

    /**
     * Record blocked request.
     */
    protected function recordBlockedRequest(
        Request $request,
        int $limit,
        int $retryAfter
    ): void {
       $route = $request->route()?->uri() ?? $request->path();

        TrafficBlockLog::create([
            'ip_address' => $request->ip(),
            'method' => $request->method(),
            'route' => $route,
            'url' => $request->fullUrl(),
            'user_type' => $this->getTrafficType($request),
            'user_id' => $this->getUserId($request),
            'reason' => 'rate_limit_exceeded',
            'limit' => $limit,
            'retry_after' => $retryAfter,
        ]);

        $date = now()->toDateString();
        $minute = (int) now()->format('Hi');

        $stat = TrafficStatistic::firstOrCreate(
            [
                'stat_date' => $date,
                'stat_minute' => $minute,
            ],
            [
                'total_requests' => 0,
                'blocked_requests' => 0,
                'public_requests' => 0,
                'customer_requests' => 0,
                'admin_requests' => 0,
                'total_response_time' => 0,
                'max_response_time' => 0,
            ]
        );

        $stat->increment('blocked_requests');
    }

    protected function getTrafficType(Request $request): string
    {
        if ($request->user()) {
            return 'admin';
        }

        if (auth('customer')->check()) {
            return 'customer';
        }

        return 'public';
    }

    protected function getUserId(Request $request): ?int
    {
        if ($request->user()) {
            return $request->user()->id;
        }

        if (auth('customer')->check()) {
            return auth('customer')->id();
        }

        return null;
    }
}