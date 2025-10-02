<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogAnalytics;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', '30'); // Default to 30 days
        $startDate = now()->subDays($period);

        // Overview Statistics
        $stats = [
            'total_views' => BlogAnalytics::views()->where('event_date', '>=', $startDate)->count(),
            'total_likes' => BlogAnalytics::likes()->where('event_date', '>=', $startDate)->count(),
            'total_comments' => BlogAnalytics::comments()->where('event_date', '>=', $startDate)->count(),
            'unique_visitors' => BlogAnalytics::where('event_date', '>=', $startDate)
                ->whereNotNull('user_id')
                ->distinct('user_id')
                ->count(),
        ];

        // Daily Analytics for Chart
        $dailyAnalytics = BlogAnalytics::select(
            DB::raw('DATE(event_date) as date'),
            DB::raw('COUNT(CASE WHEN event_type = "view" THEN 1 END) as views'),
            DB::raw('COUNT(CASE WHEN event_type = "like" THEN 1 END) as likes'),
            DB::raw('COUNT(CASE WHEN event_type = "comment" THEN 1 END) as comments')
        )
        ->where('event_date', '>=', $startDate)
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Top Performing Blogs
        $topBlogs = Blog::select('blogs.*')
            ->join('blog_analytics', 'blogs.id', '=', 'blog_analytics.blog_id')
            ->where('blog_analytics.event_date', '>=', $startDate)
            ->where('blog_analytics.event_type', 'view')
            ->groupBy('blogs.id')
            ->orderByRaw('COUNT(blog_analytics.id) DESC')
            ->take(10)
            ->withCount(['analytics as views_count' => function($query) use ($startDate) {
                $query->where('event_type', 'view')->where('event_date', '>=', $startDate);
            }])
            ->get();

        // Device Analytics
        $deviceStats = BlogAnalytics::select('device_type', DB::raw('COUNT(*) as count'))
            ->where('event_date', '>=', $startDate)
            ->where('event_type', 'view')
            ->groupBy('device_type')
            ->get();

        // Browser Analytics
        $browserStats = BlogAnalytics::select('browser', DB::raw('COUNT(*) as count'))
            ->where('event_date', '>=', $startDate)
            ->where('event_type', 'view')
            ->whereNotNull('browser')
            ->groupBy('browser')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();

        // Referrer Analytics
        $referrerStats = BlogAnalytics::select('referrer', DB::raw('COUNT(*) as count'))
            ->where('event_date', '>=', $startDate)
            ->where('event_type', 'view')
            ->whereNotNull('referrer')
            ->where('referrer', '!=', '')
            ->groupBy('referrer')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();

        return view('admin.analytics.index', compact(
            'stats',
            'dailyAnalytics',
            'topBlogs',
            'deviceStats',
            'browserStats',
            'referrerStats',
            'period'
        ));
    }

    public function blogDetails(Blog $blog, Request $request)
    {
        $period = $request->get('period', '30');
        $startDate = now()->subDays($period);

        // Blog-specific analytics
        $analytics = $blog->analytics()
            ->where('event_date', '>=', $startDate)
            ->get();

        $stats = [
            'views' => $analytics->where('event_type', 'view')->count(),
            'likes' => $analytics->where('event_type', 'like')->count(),
            'comments' => $analytics->where('event_type', 'comment')->count(),
            'unique_visitors' => $analytics->where('event_type', 'view')->unique('user_id')->count(),
        ];

        // Daily views for this blog
        $dailyViews = $blog->analytics()
            ->select(
                DB::raw('DATE(event_date) as date'),
                DB::raw('COUNT(*) as views')
            )
            ->where('event_type', 'view')
            ->where('event_date', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.analytics.blog-details', compact(
            'blog',
            'stats',
            'dailyViews',
            'period'
        ));
    }

    public function export(Request $request)
    {
        $period = $request->get('period', '30');
        $startDate = now()->subDays($period);

        $analytics = BlogAnalytics::with(['blog', 'user'])
            ->where('event_date', '>=', $startDate)
            ->get();

        $csvData = [];
        $csvData[] = ['Date', 'Blog Title', 'Event Type', 'User', 'Device Type', 'Browser', 'IP Address'];

        foreach ($analytics as $analytic) {
            $csvData[] = [
                $analytic->event_date->format('Y-m-d H:i:s'),
                $analytic->blog?->title ?? 'Unknown',
                $analytic->event_type,
                $analytic->user?->name ?? 'Guest',
                $analytic->device_type ?? 'Unknown',
                $analytic->browser ?? 'Unknown',
                $analytic->ip_address ?? 'Unknown',
            ];
        }

        $filename = "blog_analytics_" . now()->format('Y-m-d_H-i-s') . ".csv";

        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
        ]);
    }
}