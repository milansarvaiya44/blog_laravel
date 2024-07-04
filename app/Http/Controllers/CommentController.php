<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\CommentResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelper;


class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    public function index()
    {
        $comments = Comment::with(['user', 'post'])->get();
        return ResponseHelper::successResponse(CommentResource::collection($comments));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
            'post_id' => 'required|exists:posts,id',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::errorResponse($validator->errors());
        }

        $comment = Comment::create([
            'content' => $request->input('content'),
            'post_id' => $request->input('post_id'),
            'user_id' => Auth::id(),
        ]);

        // return new CommentResource($comment->load(['user', 'post']));
        return ResponseHelper::successResponse(new CommentResource($comment->load(['user', 'post'])));
    }

    public function show($id)
    {
        $comment = Comment::with(['user', 'post'])->find($id);
        return ResponseHelper::successResponse(new CommentResource($comment));
    }

    public function customUpdate(Request $request, $id)
    {
        $comment = Comment::find($id);
        if(!$comment){
            return response()->json(['error' => true,'message' => 'record not found'], 200);
        }

        if ($comment->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::errorResponse($validator->errors());
        }

        $comment->update($request->all());

        return ResponseHelper::successResponse(new CommentResource($comment->load(['user', 'post'])));
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);
        if(!$comment){
            return response()->json(['error' => true,'message' => 'record not found'], 200);
        }

        if ($comment->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return ResponseHelper::successResponse([],'record deleted successfully');
    }
}
