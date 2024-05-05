<?php

namespace App\Jobs;

use App\Models\Post_Tag;
use App\Models\Tag;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DeleteTagJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

     public $tries = 3;

    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $tags = Tag::all();
        $tagsExistInPost = Post_Tag::pluck('tagId')->toArray();
        foreach($tags as $tag) {
            if(!in_array($tag, $tagsExistInPost)){
                $tag->delete();
            }
        }
    }

    public function failed(): void
    {
        //
    }
}