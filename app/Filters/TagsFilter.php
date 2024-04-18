<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

Class TagsFilter extends ApiFilter {
    protected $allowParamsFilter = [
        'title',
        'metaTitle',
        'slug',
    ];

    protected $allowParamsSort = [];

    protected $columnMap = [];
}