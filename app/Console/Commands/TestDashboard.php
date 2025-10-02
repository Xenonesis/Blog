<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;

class TestDashboard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-dashboard';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the admin dashboard controller';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Admin Dashboard Controller...');
        
        try {
            $controller = app(AdminController::class);
            $request = Request::create('/admin', 'GET');
            
            // Test if the controller method exists
            if (method_exists($controller, 'index')) {
                $this->info('✓ AdminController::index method exists');
            } else {
                $this->error('✗ AdminController::index method not found');
                return;
            }
            
            // Try to call the method
            $result = $controller->index($request);
            $this->info('✓ AdminController::index executed successfully');
            
            // Check if result is valid
            if (is_array($result) || is_object($result)) {
                $this->info('✓ Controller returned data');
                
                // Check for recent_comments key
                if (is_array($result) && isset($result['recent_comments'])) {
                    $comments = $result['recent_comments'];
                    $this->info('✓ recent_comments found in response');
                    $this->info('Number of recent comments: ' . (is_countable($comments) ? count($comments) : 'not countable'));
                } else {
                    $this->warn('⚠ recent_comments not found in response');
                }
            } else {
                $this->warn('⚠ Controller did not return expected data structure');
            }
            
        } catch (\Exception $e) {
            $this->error('✗ Error testing dashboard: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
    }
}
