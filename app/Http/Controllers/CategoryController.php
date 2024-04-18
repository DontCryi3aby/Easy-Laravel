<?php

namespace App\Http\Controllers;

use App\Filters\CategoriesFilter;
use App\Http\Resources\CategoryCollection;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new CategoriesFilter();
        
        [$sort, $queryItems] = $filter->transform($request);
        
        // Filter
        $categories = Category::where($queryItems);

        // Sort
        if($sort['field']) {
            $categories = $categories->orderBy($sort['field'], $sort['type']);
        }

        return new CategoryCollection($categories->paginate()->withQueryString());
    }
    
    public function store(StoreCategoryRequest $request)
    {
        $title = $request->title;
        if(!$request->slug) {
            $slug = Str::replace(' ', "-", Str::lower($title));
            $request->request->add(['slug' => $slug]);
        }

        if(!$request->metaTitle) {
            $request->request->add(['metaTitle' => $title]);
        }

        return new CategoryResource(Category::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return new CategoryResource($category);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        
        if($request->isMethod('put')){
            $title = $request->title;
            if(!$request->slug) {
                $slug = Str::replace(' ', "-", Str::lower($title));
                $request->request->add(['slug' => $slug]);
            }
    
            if(!$request->metaTitle) {
                $request->request->add(['metaTitle' => $title]);
            }
        }
        
        $category->update($request->all());
        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json([
            'status'=> 200,
            'message' => "Category deleted successfully!"
        ]);
    }
}