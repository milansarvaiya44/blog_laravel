<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $posts = Post::where('published', true)->with(['user', 'category', 'tags', 'comments'])->get();
        return ResponseHelper::successResponse(PostResource::collection($posts));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'slug' => 'required|string|max:255|unique:posts',
            'category_id' => 'required|exists:categories,id',
            'featured_image' => 'required|image|max:2048',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'published' => 'boolean',
        ]);

        if ($validator->fails()) {
          return ResponseHelper::errorResponse($validator->errors());
        }   

        $data = $request->all();
        $data['user_id'] = Auth::id();

        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/images', $imageName); 
            $data['featured_image'] = 'storage/images/' . $imageName;
        }
        $data['published'] = true;

        $post = Post::create($data);

        if ($request->has('tags')) {
          $post->tags()->attach($request->tags);
        }

        return ResponseHelper::successResponse(new PostResource($post->load(['user', 'category', 'tags', 'comments'])));
    }

    public function show($id)
    {
        // die('ss');
        $post = Post::with(['user', 'category', 'tags', 'comments'])->find($id);
        
        if(!$post){
            return response()->json(['error' => true,'message' => 'record not found'], 200);
        }
        if ($post->published || $post->user_id == Auth::id()) {
             return ResponseHelper::successResponse(new PostResource($post));
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }

    public function customUpdate(Request $request, $id)
    {
        $post = Post::find($id);
        if(!$post){
            return response()->json(['error' => true,'message' => 'record not found'], 200);            
        }

        if ($post->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'slug' => 'required|string|max:255|unique:posts,slug,' . $post->id,
            'category_id' => 'required|exists:categories,id',
            'featured_image' => 'nullable|image|max:2048',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::errorResponse($validator->errors());
        }

        $data = $request->except('published'); 
        $post->update($data);

        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/images', $imageName); 

            $post->featured_image = 'storage/images/' . $imageName;
            $post->save();
        }
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }
     
        return ResponseHelper::successResponse(new PostResource($post->load(['user', 'category', 'tags', 'comments'])));
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $post->delete();

        return ResponseHelper::successResponse([],'record deleted successfully');
    }
}