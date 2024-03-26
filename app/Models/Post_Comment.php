<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post_Comment extends Model
{
    use HasFactory;

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function parent()
    {
        return $this->belongsTo('Post_Comment', 'parentId');
    }

    public function children()
    {
        return $this->hasMany('Post_Comment', 'parentId');
    }
}