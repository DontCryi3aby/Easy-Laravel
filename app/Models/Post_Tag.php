<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post_Tag extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];
    protected $table = 'Post_Tags';

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }
}