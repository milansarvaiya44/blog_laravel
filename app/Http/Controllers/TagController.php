<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Resources\TagResource;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelper;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    public function index()
    {
        $tags = Tag::all();
        return ResponseHelper::successResponse(TagResource::collection($tags));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:tags',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::errorResponse($validator->errors());
        }

        $tag = Tag::create($request->all());

        return ResponseHelper::successResponse(TagResource($tag));
    }

    public function show($id)
    {
        $tag = Tag::find($id);
        if(!$tag){
            return response()->json(['error' => true,'message' => 'record not found'], 200);            
        }
        return ResponseHelper::successResponse(TagResource($tag));
    }

    public function customUpdate(Request $request, $id)
    {
        $tag = Tag::find($id);
        if(!$tag){
            return response()->json(['error' => true,'message' => 'record not found'], 200);            
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:tags,name,' . $tag->id,
        ]);

        if ($validator->fails()) {
            return ResponseHelper::errorResponse($validator->errors());
        }

        $tag->update($request->all());

        return ResponseHelper::successResponse(TagResource($tag));

    }

    public function destroy($id)
    {
        $tag = Tag::find($id);
        if(!$tag){
            return response()->json(['error' => true,'message' => 'record not found'], 200);            
        }
        
        $tag->delete();

        return ResponseHelper::successResponse([],'record deleted successfully');
    }
}
