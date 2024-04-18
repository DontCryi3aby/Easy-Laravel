<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

Class PostsFilter extends ApiFilter {
    protected $allowParamsFilter = [
        'authorId',
        'title',
        'metaTitle',
        'slug',
        'published',
    ];

    protected $allowParamsSort = [
        'createdAt'
    ];

    protected $columnMap = [];
}