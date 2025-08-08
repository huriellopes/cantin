<?php

namespace App\Console\Commands;

use App\Enum\StatusPost;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckPostPublishFuture extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:check-post-publish-future';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $posts = Post::query()
            ->where('published_at', '<=', Carbon::now())
            ->where('status', '=', StatusPost::PENDING)
            ->get();

        foreach ($posts as $post) {
            if ($post->published_at->format('Y-m-d') === Carbon::now()->format('Y-m-d')) {
                dump('teste');
            }
        }
        dd('');
    }
}
