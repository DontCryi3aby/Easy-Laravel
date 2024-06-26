<?php

namespace App\Observers;

use App\Constants\CACHE_KEY;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;

class TagObserver
{
    /**
     * Handle the Tag "created" event.
     */
    public function created(Tag $tag): void
    {
        Cache::forget(CACHE_KEY::TAGS->value);

        Cache::rememberForever(CACHE_KEY::TAGS->value, function () {
            $tags = Tag::orderBy('id', 'desc')->paginate(15);
            return $tags;
        });
    }

    /**
     * Handle the Tag "updated" event.
     */
    public function updated(Tag $tag): void
    {
        Cache::forget(CACHE_KEY::TAGS->value);

        Cache::rememberForever(CACHE_KEY::TAGS->value, function () {
            $tags = Tag::orderBy('id', 'desc')->paginate(15);
            return $tags;
        });
    }

    /**
     * Handle the Tag "deleted" event.
     */
    public function deleted(Tag $tag): void
    {
        Cache::forget(CACHE_KEY::TAGS->value);

        Cache::rememberForever(CACHE_KEY::TAGS->value, function () {
            $tags = Tag::orderBy('id', 'desc')->paginate(15);
            return $tags;
        });
    }

    /**
     * Handle the Tag "restored" event.
     */
    public function restored(Tag $tag): void
    {
        //
    }

    /**
     * Handle the Tag "force deleted" event.
     */
    public function forceDeleted(Tag $tag): void
    {
        //
    }
}