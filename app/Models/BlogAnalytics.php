<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogAnalytics extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_id',
        'event_type',
        'user_id',
        'ip_address',
        'user_agent',
        'referrer',
        'country',
        'city',
        'device_type',
        'browser',
        'metadata',
        'event_date',
    ];

    protected $casts = [
        'metadata' => 'array',
        'event_date' => 'datetime',
    ];

    // Relationships
    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeViews($query)
    {
        return $query->where('event_type', 'view');
    }

    public function scopeLikes($query)
    {
        return $query->where('event_type', 'like');
    }

    public function scopeComments($query)
    {
        return $query->where('event_type', 'comment');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('event_date', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('event_date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('event_date', now()->month)
                    ->whereYear('event_date', now()->year);
    }

    // Static methods for tracking
    public static function trackEvent($blogId, $eventType, $metadata = [])
    {
        $analytics = new self();
        $analytics->blog_id = $blogId;
        $analytics->event_type = $eventType;
        $analytics->user_id = auth()->id();
        $analytics->ip_address = request()->ip();
        $analytics->user_agent = request()->userAgent();
        $analytics->referrer = request()->header('referer');
        $analytics->device_type = self::detectDeviceType(request()->userAgent());
        $analytics->browser = self::detectBrowser(request()->userAgent());
        $analytics->metadata = $metadata;
        $analytics->event_date = now();
        $analytics->save();

        return $analytics;
    }

    private static function detectDeviceType($userAgent)
    {
        if (preg_match('/Mobile|Android|iPhone|iPad/', $userAgent)) {
            if (preg_match('/iPad/', $userAgent)) {
                return 'tablet';
            }
            return 'mobile';
        }
        return 'desktop';
    }

    private static function detectBrowser($userAgent)
    {
        if (preg_match('/Chrome/', $userAgent)) return 'Chrome';
        if (preg_match('/Firefox/', $userAgent)) return 'Firefox';
        if (preg_match('/Safari/', $userAgent)) return 'Safari';
        if (preg_match('/Edge/', $userAgent)) return 'Edge';
        return 'Other';
    }
}
