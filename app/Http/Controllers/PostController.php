<?php

namespace App\Http\Controllers;

use App\Events\PostDeletionEvent;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use App\Mail\PostCreation;
use App\Notifications\PostDeletionNotification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){

        // if(Cache::has('posts')){
        //     $posts = Cache::get('posts');
        // }else{
        //     Cache::put('posts', Post::get(), 10);
        //     $posts = Cache::get('posts');
        // }


        $posts = Cache::remember('posts', 10, function(){
            return Post::get();
        } );

        // $posts = Cache::flexible('posts', [5, 10], function(){
        //     return Post::get();
        // } );

        return response()->json([
            'statusCode' => 200,
            'data' => [
                'posts' => PostResource::collection($posts),
            ],
        ], 200 );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => "required|string|unique:posts,name",
        ]);

        if($validator->fails()){
            return response()->json([
                'statusCode' => 422,
                'message' => $validator->errors(),
            ], 422);
        }

        $post = Post::create([
            'name' => $request->name,
            'user_id' => auth()->user()->id,
        ]);


        return response()->json([
            'statusCode' => 201,
            'data' => new PostResource($post),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post){
        Gate::authorize('view', $post);
        return response()->json([
            'statusCode' => 200,
            'data' => new PostResource($post),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post){
        Gate::authorize('update', $post);
        $validator = Validator::make($request->all(), [
            'name' => "required|string|unique:posts,name,".$post->id,
        ]);

        if($validator->fails()){
            return response()->json([
                'statusCode' => 422,
                'message' => $validator->errors(),
            ], 422);
        }

        $post->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'statusCode' => 200,
            'data' => new PostResource($post),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {

        Gate::authorize('delete', $post);
        $post->delete();
        $user = auth()->user();
        PostDeletionEvent::dispatch($user);
        return response()->json([
            'statusCode' => 200,
            'data' => 'post deleted successfully',
        ], 200);
    }
}
