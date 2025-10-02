<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommentReceived extends Notification implements ShouldQueue
{
    use Queueable;

    protected $comment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
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
            ->subject('New Comment on: ' . $this->comment->blog->title)
            ->greeting('Hello!')
            ->line('You have received a new comment on your blog post.')
            ->line('**' . $this->comment->blog->title . '**')
            ->line('Comment by: ' . $this->comment->user->name)
            ->line('"' . \Str::limit($this->comment->content, 100) . '"')
            ->action('View Comment', route('blogs.show', $this->comment->blog->slug))
            ->line('Thank you for your engagement!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'comment_id' => $this->comment->id,
            'blog_id' => $this->comment->blog->id,
            'blog_title' => $this->comment->blog->title,
            'blog_slug' => $this->comment->blog->slug,
            'commenter_name' => $this->comment->user->name,
            'comment_preview' => \Str::limit($this->comment->content, 50),
            'message' => $this->comment->user->name . ' commented on your blog: ' . $this->comment->blog->title,
        ];
    }
}
