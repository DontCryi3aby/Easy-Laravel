<?php

namespace App\Http\Controllers;

use App\Filters\PostsFilter;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new PostsFilter();
        
        [$sort, $queryItems] = $filter->transform($request);
        
        // Filter
        $posts = Post::where($queryItems);

        // Sort
        if($sort['field']) {
            $posts = $posts->orderBy($sort['field'], $sort['type']);
        }

        return new PostCollection(
            $posts
                ->when($request->has('_startTime'), function ($posts) use ($request) {
                    return $posts->where('createdAt', '>=', $request->_startTime);
                })
                ->when($request->has('_endTime'), function ($posts) use ($request) {
                    return $posts->where('createdAt', '<=', $request->_endTime);
                })
                ->paginate()->withQueryString());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $title = $request->title;
        if(!$request->slug) {
            $slug = Str::replace(' ', "-", Str::lower($title));
            $request->request->add(['slug' => $slug]);
        }

        if(!$request->metaTitle) {
            $request->request->add(['metaTitle' => $title]);
        }

        return new PostResource(Post::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $post = Post::findOrFail($id);
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, $id)
    {
        $post = Post::findOrFail($id);
        
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

        $post->update($request->all());
        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return response()->json([
            'status'=> 200,
            'message' => "Post deleted successfully!"
        ]);
    }
}