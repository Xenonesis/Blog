<?php

namespace App\Notifications;

use App\Models\Blog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBlogPublished extends Notification implements ShouldQueue
{
    use Queueable;

    protected $blog;

    /**
     * Create a new notification instance.
     */
    public function __construct(Blog $blog)
    {
        $this->blog = $blog;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Blog Published: ' . $this->blog->title)
            ->greeting('Hello!')
            ->line('A new blog post has been published on ' . config('app.name'))
            ->line('**' . $this->blog->title . '**')
            ->line($this->blog->excerpt ?: 'Check out this amazing new post!')
            ->line('Author: ' . $this->blog->user->name)
            ->action('Read Now', route('blogs.show', $this->blog->slug))
            ->line('Thank you for being part of our community!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'blog_id' => $this->blog->id,
            'blog_title' => $this->blog->title,
            'blog_slug' => $this->blog->slug,
            'author_name' => $this->blog->user->name,
            'message' => 'New blog published: ' . $this->blog->title,
        ];
    }
}
