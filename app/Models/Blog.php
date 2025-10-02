<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'cover_image',
        'user_id',
        'category_id',
        'is_published',
        'is_hidden',
        'published_at',
        // SEO fields
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'social_image',
        'social_description',
        'reading_time',
        'seo_score',
        // Scheduling fields
        'scheduled_at',
        'status',
        'auto_publish',
        'scheduling_settings',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_hidden' => 'boolean',
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'auto_publish' => 'boolean',
        'seo_score' => 'array',
        'scheduling_settings' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title);
            }
            if (empty($blog->published_at) && $blog->is_published) {
                $blog->published_at = now();
            }
        });

        static::updated(function ($blog) {
            // Send notification when blog is published for the first time
            if ($blog->is_published && !$blog->is_hidden && $blog->wasChanged('is_published')) {
                // Notify all users about new blog (you can customize this logic)
                $users = \App\Models\User::where('id', '!=', $blog->user_id)->get();
                \Notification::send($users, new \App\Notifications\NewBlogPublished($blog));
            }
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function analytics()
    {
        return $this->hasMany(BlogAnalytics::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true)->where('is_hidden', false);
    }

    public function scopeVisible($query)
    {
        return $query->where('is_hidden', false);
    }

    // Accessors
    public function getLikesCountAttribute()
    {
        return $this->likes()->where('type', 'like')->count();
    }

    public function getDislikesCountAttribute()
    {
        return $this->likes()->where('type', 'dislike')->count();
    }

    public function getCommentsCountAttribute()
    {
        return $this->comments()->where('status', 'approved')->count();
    }

    // SEO Methods
    public function calculateReadingTime()
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $readingTime = ceil($wordCount / 200); // Average reading speed: 200 words per minute
        $this->update(['reading_time' => $readingTime]);
        return $readingTime;
    }

    public function generateSeoScore()
    {
        $score = 0;
        $recommendations = [];

        // Title length check (50-60 characters)
        $titleLength = strlen($this->title);
        if ($titleLength >= 50 && $titleLength <= 60) {
            $score += 20;
        } else {
            $recommendations[] = 'Title should be 50-60 characters long';
        }

        // Meta description check (150-160 characters)
        if ($this->meta_description) {
            $metaLength = strlen($this->meta_description);
            if ($metaLength >= 150 && $metaLength <= 160) {
                $score += 20;
            } else {
                $recommendations[] = 'Meta description should be 150-160 characters long';
            }
        } else {
            $recommendations[] = 'Add a meta description';
        }

        // Content length check (minimum 300 words)
        $wordCount = str_word_count(strip_tags($this->content));
        if ($wordCount >= 300) {
            $score += 20;
        } else {
            $recommendations[] = 'Content should be at least 300 words';
        }

        // Image alt text check
        if ($this->cover_image) {
            $score += 10;
        } else {
            $recommendations[] = 'Add a cover image';
        }

        // Keywords in title
        if ($this->meta_keywords) {
            $keywords = explode(',', $this->meta_keywords);
            foreach ($keywords as $keyword) {
                if (stripos($this->title, trim($keyword)) !== false) {
                    $score += 10;
                    break;
                }
            }
        } else {
            $recommendations[] = 'Add meta keywords';
        }

        // Internal/external links
        $linkCount = substr_count($this->content, '<a ');
        if ($linkCount > 0) {
            $score += 10;
        } else {
            $recommendations[] = 'Add some internal or external links';
        }

        $seoData = [
            'score' => min($score, 100),
            'recommendations' => $recommendations,
            'last_analyzed' => now()->toISOString(),
        ];

        $this->update(['seo_score' => $seoData]);
        return $seoData;
    }

    // Analytics Methods
    public function trackView($metadata = [])
    {
        return BlogAnalytics::trackEvent($this->id, 'view', $metadata);
    }

    public function trackLike($metadata = [])
    {
        return BlogAnalytics::trackEvent($this->id, 'like', $metadata);
    }

    public function trackComment($metadata = [])
    {
        return BlogAnalytics::trackEvent($this->id, 'comment', $metadata);
    }

    // Scheduling Methods
    public function isScheduled()
    {
        return $this->status === 'scheduled' && $this->scheduled_at && $this->scheduled_at->isFuture();
    }

    public function canBePublished()
    {
        return $this->status === 'scheduled' && $this->scheduled_at && $this->scheduled_at->isPast();
    }
}
