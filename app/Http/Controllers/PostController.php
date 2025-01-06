<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    //add post

    public function addNewPost(Request $request){

        $validated= Validator::make($request->all(),[

            'title'=>'required|string|max:255',
            'content'=>'required|string|min:3',
           // 'user_id'=>'required|integer',

        ]);

        if($validated->fails()){
            return response()->json($validated->errors(),422);
        }

        try {

            //code...
            $post = new Post();
            $post->title = $request->title;
            $post->content = $request->content;
            $post->user_id = auth()->user()->id;
            $post->save();

            return response()->json([
                'message'=>'Post added successfully',
                'post_id'=>$post->id,
                'post_data'=>$post,
    
            ],200);


        } catch (\Throwable $th) {
            return response()->json(['error'=>$th->getMessage()],403);
            //throw $th;
        }


    }

    // public function updatepost(Request $request){

    //     $validated= Validator::make($request->all(),[

    //         'title'=>'required|string|max:255',
    //         'content'=>'required|string|min:3',
    //         'post_id'=>'required|integer',

    //     ]);

    //     if($validated->fails()){
    //         return response()->json($validated->errors(),422);
    //     }

    //     try {

    //         //code...

    //         $post_data = Post::find($request->id);

    //        $updatePost= $post_data->update([

                
    //         'title'=>$request->title,
    //         'content'=>$request->content

    //         ]);
  

    //         return response()->json([
    //             'message'=>'Post updated successfully',
    //             'updated_post'=>$updatePost
                
    
    //         ],200);


    //     } catch (\Throwable $th) {
    //         return response()->json(['error'=>$th->getMessage()],403);
    //         //throw $th;
    //     }
    // }

    public function updatePost(Request $request)
{
    // Validate the incoming request
    $validated = Validator::make($request->all(), [
        'post_id' => 'required|integer|exists:posts,id', // Ensure post exists
        'title' => 'required|string|max:255',
        'content' => 'required|string|min:3',
    ]);

    if ($validated->fails()) {
        return response()->json($validated->errors(), 422);
    }

    try {
        // Find the post by ID
        $post = Post::find($request->post_id);

        // Ensure the authenticated user is the owner of the post
        if (auth()->user()->id !== $post->user_id) {
            return response()->json(['error' => 'Unauthorized action'], 403);
        }

        // Update the post details
        $post->title = $request->title;
        $post->content = $request->content;
        $post->save();

        return response()->json([
            'message' => 'Post updated successfully',
            'post_data' => $post,
        ], 200);
    } catch (\Throwable $th) {
        return response()->json(['error' => $th->getMessage()], 500);
    }
}


public function updatePost2(Request $request,$post_id)
{
    // Validate the incoming request
    $validated = Validator::make($request->all(), [
       // 'post_id' => 'required|integer|exists:posts,id', // Ensure post exists
        'title' => 'required|string|max:255',
        'content' => 'required|string|min:3',
    ]);

    if ($validated->fails()) {
        return response()->json($validated->errors(), 422);
    }

    try {
        // Find the post by ID
        $post = Post::find($post_id);

        // Ensure the authenticated user is the owner of the post
        if (auth()->user()->id !== $post->user_id) {
            return response()->json(['error' => 'Unauthorized action'], 403);
        }

        // Update the post details
        $post->title = $request->title;
        $post->content = $request->content;
        $post->save();

        return response()->json([
            'message' => 'Post updated successfully',
            'post_data' => $post,
        ], 200);
    } catch (\Throwable $th) {
        return response()->json(['error' => $th->getMessage()], 500);
    }
}
//retrieve all posts

public function getAllPosts($post_id){

    try {
        //code...
        $posts = Post::all();
        return response()->json([

            'posts' =>$posts

        ],200);
    } catch (\Exception $exception) {
        return response()->json(['error'=>$exception]);
        //throw $th;
    }
}

public function getPost($post_id){

    try {
        $post = Post::where('id',$post_id)->first();
        return response()->json([

            'posts'=>$post

        ],200);
    } catch (\Throwable $th) {
        //throw $th;
        return response()->json(['error'=>$th->getMessage()],403);
    }
}






}
