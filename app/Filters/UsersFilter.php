<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

Class UsersFilter extends ApiFilter {
    protected $allowParamsFilter = [
        'firstName',
        'middleName',
        'lastName',
        'mobile',
        'email'
    ];

    protected $allowParamsSort = [
        'registedAt',
        'firstName'
    ];

    protected $columnMap = [];
}