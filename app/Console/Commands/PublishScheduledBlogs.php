<?php

namespace App\Console\Commands;

use App\Models\Blog;
use App\Notifications\NewBlogPublished;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class PublishScheduledBlogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blogs:publish-scheduled {--dry-run : Show what would be published without actually publishing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish blogs that are scheduled for the current time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        $scheduledBlogs = Blog::where('status', 'scheduled')
            ->where('auto_publish', true)
            ->where('scheduled_at', '<=', now())
            ->get();

        if ($scheduledBlogs->isEmpty()) {
            $this->info('No blogs scheduled for publishing at this time.');
            return 0;
        }

        $this->info("Found {$scheduledBlogs->count()} blog(s) to publish:");

        foreach ($scheduledBlogs as $blog) {
            $this->line("- {$blog->title} (scheduled for {$blog->scheduled_at->format('Y-m-d H:i:s')})");
            
            if (!$dryRun) {
                // Update blog status
                $blog->update([
                    'status' => 'published',
                    'is_published' => true,
                    'published_at' => now(),
                ]);

                // Send notifications
                $users = \App\Models\User::where('id', '!=', $blog->user_id)->get();
                Notification::send($users, new NewBlogPublished($blog));

                $this->info("✓ Published: {$blog->title}");
            } else {
                $this->line("  → Would publish: {$blog->title}");
            }
        }

        if ($dryRun) {
            $this->warn('This was a dry run. Use --no-dry-run to actually publish the blogs.');
        } else {
            $this->info("Successfully published {$scheduledBlogs->count()} blog(s)!");
        }

        return 0;
    }
}
