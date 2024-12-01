<?php

namespace App\Jobs;

use App\Models\Thread;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Http\Requests\ThreadStoreRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateThread implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $thread;
    private $attributes;

    /**
     * Create a new job instance.
     *
     * @param Thread $thread
     * @param array $attributes
     */
    public function __construct(Thread $thread, array $attributes = [])
    {
        $this->thread = $thread;
        $this->attributes = Arr::only($attributes, [
            'title', 'slug', 'body', 'category_id', 'tags'
        ]);
    }

    /**
     * Create a new job from request.
     *
     * @param Thread $thread
     * @param ThreadStoreRequest $request
     * @return static
     */
    public static function fromRequest(Thread $thread, ThreadStoreRequest $request): self
    {
        return new static($thread, [
            'title'         => $request->title(),
            'body'          => strip_tags(Purifier::clean($request->body())), // Remove unwanted HTML tags
            'category_id'   => $request->category(),
            'slug'          => Str::slug($request->title()),
            'tags'          => $request->tags(),
        ]);
    }

    /**
     * Execute the job.
     *
     * @return Thread
     */
    public function handle(): Thread
    {
        $this->thread->update($this->attributes);

        if (Arr::has($this->attributes, 'tags')) {
            $this->thread->syncTags($this->attributes['tags']);
        }

        return $this->thread;
    }
}
