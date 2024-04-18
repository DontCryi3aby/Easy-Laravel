<?php

namespace App\Http\Controllers;

use App\Filters\UsersFilter;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new UsersFilter();
        
        [$sort, $queryItems] = $filter->transform($request);
        
        // Filter
        $users = User::where($queryItems);

        // Sort
        if($sort['field']) {
            $users = $users->orderBy($sort['field'], $sort['type']);
        }

        // if($request->query("_embed") == "posts") {
        //     $users = $users->with('posts');
        // }
        
        return new UserCollection($users->paginate()->withQueryString());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        return new UserResource(User::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json([
            'status'=> 200,
            'message' => "User deleted successfully!"
        ]);
    }
}