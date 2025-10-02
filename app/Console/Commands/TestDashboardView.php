<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;

class TestDashboardView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-dashboard-view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test dashboard view rendering with debug output';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing dashboard view rendering...');

        // Create a mock request
        $request = new Request();

        // Create controller instance
        $controller = new AdminController();

        // Call the index method
        $response = $controller->index();

        $this->info('Controller executed successfully');

        // Get the view data
        $view = $response;
        $data = $view->getData();

        $this->info('View data extracted:');
        $this->line('Available variables: ' . implode(', ', array_keys($data)));

        if (isset($data['recent_comments'])) {
            $comments = $data['recent_comments'];
            $this->info("Recent comments count: " . $comments->count());

            if ($comments->count() > 0) {
                $this->info("First comment content: " . substr($comments->first()->content, 0, 50) . "...");
                $this->info("First comment user: " . ($comments->first()->user ? $comments->first()->user->name : 'null'));
                $this->info("First comment blog: " . ($comments->first()->blog ? $comments->first()->blog->title : 'null'));
            }
        } else {
            $this->error('recent_comments variable not found in view data!');
        }

        $this->info('Test completed.');
    }
}
