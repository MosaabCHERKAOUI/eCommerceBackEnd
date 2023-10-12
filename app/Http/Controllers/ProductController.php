<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():JsonResponse
    {
        $products = Product::all();
        if($products->isEmpty()) {
            return response()->json('No Products Found', JsonResponse::HTTP_NOT_FOUND);
        }
        return response()->json($products, JsonResponse::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request):JsonResponse
    {
        try {
            $this->validate($request, [
                'name'=> 'string',
                'price'=> 'float',
                'details'=> 'string',
                'picture'=> 'string',
                'rating'=> 'string',
                'category'=> 'integer'
            ]);
        } catch (ValidationException $e) {
            return response()->json($e->errors(), JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        $product = Product::create([
            'name'=> $request->input('name'),
            'price'=> $request->input('price'),
            'details'=> $request->input('details'),
            'rating'=> $request->input('rating'),
            'category'=> $request->input('category')
        ]);
        return response()->json(['msg'=>'Product Created Successfully', 'product'=> $product], JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id):JsonResponse
    {
        $product = Product::find($id);
        if(!$product) {
            return response()->json('Product Not Found', JsonResponse::HTTP_NOT_FOUND);
        }
        return response()->json($product, JsonResponse::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id):JsonResponse
    {
        $product = Product::find($id);
        if(!$product) {
            return response()->json('Product Not Found', JsonResponse::HTTP_NOT_FOUND);
        }
        try {
            $this->validate($request, [
                'name'=> 'string',
                'price'=> 'float',
                'details'=> 'string',
                'picture'=> 'string',
                'rating'=> 'string',
                'category'=> 'integer'
            ]);
        } catch (ValidationException $e) {
            return response()->json($e->errors(), JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        $product->fill($request->only([
            'name',
            'price',
            'details',
            'picture',
            'rating',
            'category'
        ]));
        return response()->json('Product Updated Successfully', JsonResponse::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id):JsonResponse
    {
        $product = Product::find($id);
        if(!$product) {
            return response()->json('Product Not Found', JsonResponse::HTTP_NOT_FOUND);
        }
        $product->delete();
        return response()->json('Product Deleted', JsonResponse::HTTP_NO_CONTENT);
    }
}
