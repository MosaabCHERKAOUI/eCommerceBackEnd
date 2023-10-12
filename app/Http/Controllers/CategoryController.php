<?php

namespace App\Http\Controllers;

use App\Models\category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $category = Category::all();
        if($category->isEmpty()) {
            return response()->json('Category Not Found', JsonResponse::HTTP_NOT_FOUND);
        } else {
            return response()->json($category, JsonResponse::HTTP_OK);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try{
            $this->validate($request, [
                'name'=> 'required|string|max:50'
            ]);
        } catch (ValidationException $e) {
            return response()->json($e->errors(), JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        $category = Category::create([
            'name'=> $request->input('name')
        ]);
        return response()->json(['Category Created', $category], JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $category = Category::find($id);
        if(!$category) {
            return response()->json('Category Not Found', JsonResponse::HTTP_NOT_FOUND);
        } else {
            return response()->json($category, JsonResponse::HTTP_OK);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $category = Category::find($id);
        if(!$category) {
            return response()->json('Category Not Found', JsonResponse::HTTP_NOT_FOUND);
        }
        try {
            $this->validate($request, [
                'name'=> 'required|string|max:50'
            ]);
        } catch (ValidationException $e) {
            return response()->json($e->errors(), JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        $category->name = $request->input('name');
        $category->save();
        return response()->json(['Category updated', $category], JsonResponse::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $category = Category::find($id);
        if(!$category) {
            return response()->json('Category Not Found', JsonResponse::HTTP_NOT_FOUND);
        } else {
            $category->delete();
            return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
        }
    }
}
