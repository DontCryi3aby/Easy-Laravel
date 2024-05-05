<?php

namespace App\Http\Controllers;

use App\Constants\CACHE_KEY;
use App\Filters\TagsFilter;
use App\Http\Resources\TagCollection;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    { 
        //Cache tags when api has no query
        if(!count($request->query())){
            if (Cache::has(CACHE_KEY::TAGS->value)) {
                $tagsCache = Cache::get(CACHE_KEY::TAGS->value);
                return new TagCollection($tagsCache);
            } else {
                Cache::rememberForever(CACHE_KEY::TAGS->value, function () {
                    $tags = Tag::orderBy('id', 'desc')->paginate(15);
                    return $tags;
                });
                $tags = Cache::get(CACHE_KEY::TAGS->value);
                return new TagCollection($tags);
            }
            
        };

        // Handle tags when api has query
        $filter = new TagsFilter();
        
        [$sort, $queryItems] = $filter->transform($request);
        
        
        // Filter
        $tags = Tag::where($queryItems);
        
        // Sort
        if($sort['field']) {
            $tags = $tags->orderBy($sort['field'], $sort['type']);
        }

        return new TagCollection($tags->paginate()->withQueryString());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request)
    {
        $title = $request->title;
        if(!$request->slug) {
            $slug = Str::replace(' ', "-", Str::lower($title));
            $request->request->add(['slug' => $slug]);
        }

        if(!$request->metaTitle) {
            $request->request->add(['metaTitle' => $title]);
        }
        
        return new TagResource(Tag::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $tag = Tag::findOrFail($id);
        return new TagResource($tag);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, $id)
    {
        $tag = Tag::findOrFail($id);

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

        $tag->update($request->all());
        return new TagResource($tag);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();
        return response()->json([
            'status'=> 200,
            'message' => "Tag deleted successfully!"
        ]);
    }
}