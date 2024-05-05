<?php

namespace App\Http\Controllers;

use App\Filters\PostsFilter;
use App\Jobs\DeleteTagJob;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Models\Post_Category;
use App\Models\Post_Tag;
use App\Models\Tag;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        // Apply DB transaction when create Post with handle tags & categories
        DB::transaction(function () use($request) {
            $newPost = Post::create($request->except(['tags', 'categories']));

            // Handle create tag
            if($tagsRequest = $request->tags){
                foreach ($tagsRequest as $tagRequest) {
                    $tagsExist = Tag::pluck('title')->toArray();
                    if(in_array($tagRequest, $tagsExist)){
                        $tagExistID = Tag::where('title', $tagRequest)->first()->id;
                        Post_Tag::create([
                            'postId' => $newPost->id,
                            'tagId' => $tagExistID,
                        ]);
                    } else {
                        $slugTag = Str::replace(' ', "-", Str::lower($tagRequest));

                        $newTag = Tag::create([
                            'title'=> $tagRequest,
                            'metaTitle'=> $tagRequest,
                            'slug'=> $slugTag,
                            'content' => null
                        ]);

                        Post_Tag::create([
                            'postId' => $newPost->id,
                            'tagId' => $newTag->id,
                        ]);
                    }
                }
            }

            // Handle create category
            if($categoriesRequest = $request->categories){
                foreach ($categoriesRequest as $categoryRequest) {
                    $categoriesExist = Category::pluck('title')->toArray();
                    if(in_array($categoryRequest, $categoriesExist)){
                        $tagExistID = Category::where('title', $categoryRequest)->first()->id;
                        Post_Category::create([
                            'postId' => $newPost->id,
                            'categoryId' => $tagExistID,
                        ]);
                    } else {
                        $slugCategory = Str::replace(' ', "-", Str::lower($categoryRequest));

                        $newCategory = Category::create([
                            'title'=> $categoryRequest,
                            'metaTitle'=> $categoryRequest,
                            'slug'=> $slugCategory,
                            'content' => null,
                            'parentId' => null
                        ]);

                        Post_Category::create([
                            'postId' => $newPost->id,
                            'categoryId' => $newCategory->id,
                        ]);
                    }
                }
            }

            return new PostResource($newPost);
        });
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
        DeleteTagJob::dispatch();
        $post = Post::findOrFail($id);
        $post->delete();
        return response()->json([
            'status'=> 200,
            'message' => "Post deleted successfully!"
        ]);
    }
}