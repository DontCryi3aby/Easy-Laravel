<?php

namespace App\Console;

use App\Models\Post;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        // Handle publish post with Scheduling
        // If post's publishedAt in future, then 1 minute after update published to 1
        $schedule->call(function () {
            $posts = Post::where('publishedAt', '<', now()->toDate())->where('published', 0)->get();
            if(count($posts) > 0) {
                foreach ($posts as $post) {
                    $post->update(['published' => 1]);
                }
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}