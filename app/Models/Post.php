<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    public $timestamps = false;


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo('Post', 'parentId');
    }

    public function children()
    {
        return $this->hasMany('Post', 'parentId');
    }

    public function post_tags(): HasMany
    {
        return $this->hasMany(Post_Tag::class);
    }

    public function post_categories(): HasMany
    {
        return $this->hasMany(Post_Category::class);
    }

    public function post_metas(): HasMany
    {
        return $this->hasMany(Post_Meta::class);
    }
}