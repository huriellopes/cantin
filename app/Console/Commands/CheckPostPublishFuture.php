<?php

namespace App\Console\Commands;

use App\Enum\StatusPost;
use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;

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
    public function handle(): void
    {
        $posts = Post::query()
            ->where('published_at', '<=', Date::now())
            ->where('status', '=', StatusPost::PENDING)
            ->get();

        foreach ($posts as $post) {
            if ($post->published_at->format('Y-m-d') === Date::now()->format('Y-m-d')) {
                dump('teste');
            }
        }
        dd('');
    }
}
