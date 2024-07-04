<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelper;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    public function index()
    {
        $categories = Category::all();
        return ResponseHelper::successResponse(CategoryResource::collection($categories));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::errorResponse($validator->errors());
        }

        $category = Category::create($request->all());

        return ResponseHelper::successResponse(new CategoryResource($category));
    }

    public function show($id)
    {
        $category = Category::find($id);

        if(!$category){
            return response()->json(['error' => true,'message' => 'record not found'], 200);
        }

        return ResponseHelper::successResponse(new CategoryResource($category));
    }

   
    public function customUpdate(Request $request, $id)
    {
        $category = Category::find($id);
        if(!$category){
            return response()->json(['error' => true,'message' => 'record not found'], 200);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        if ($validator->fails()) {
            return ResponseHelper::errorResponse($validator->errors());
        }

        $category->update($request->all());

        return ResponseHelper::successResponse(new CategoryResource($category));
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if(!$category){
            return response()->json(['error' => true,'message' => 'record not found'], 200);
        }

        $category->delete();

        return ResponseHelper::successResponse([],'record deleted successfully');
    }
}
