<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ApiFilter {
    protected $allowParamsFilter = [];
    protected $allowParamsSort = [];

    protected $columnMap = [];

    public function transform(Request $request){
        $filter = [];
        $sort = [
            'field' => '',
            'type' => 'asc' 
        ];
        
        foreach ($this->allowParamsFilter as $param) {
            if($request->query($param)){
                $queryValue = $request->query($param);
                $column = $this->columnMap[$param] ?? $param;
                if(isset($queryValue)) {
                    $filter[] = [$column, "=", $queryValue];
                }
            }        
        }
        

            if($field = $request->query('_sort')) {
                if(in_array($field, $this->allowParamsSort)){
                    $sort['field'] = $field;
                    if($request->query('_order') == 'desc') {
                        $sort['type'] = "desc";
                    }
                }
            }

        return [
            $sort,
            $filter
        ];
    }
}