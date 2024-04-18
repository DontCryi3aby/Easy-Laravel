<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

Class CategoriesFilter extends ApiFilter {
    protected $allowParamsFilter = [
        'title',
        'metaTitle',
        'slug',
        'parentId'
    ];

    protected $allowParamsSort = [];

    protected $columnMap = [];
}