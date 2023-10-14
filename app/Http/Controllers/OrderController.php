<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $client_id = Auth::user()->id;
        $orders = Order::where('id_client', $client_id)->get();
        if($orders->isEmpty()) {
            return response()->json('No order was found', JsonResponse::HTTP_NO_CONTENT);
        }
        return response()->json($orders, JsonResponse::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $client_id = Auth::user()->id;
        try{
            $order = Order::create([
                'order_date'=> date('Y-m-d'),
                'status'=> $request->status,
                'id_client'=> $client_id,
                'shipping'=> $request->shipping,
                'total'=> $request->total
            ]);
        } catch(Exception $e) {
            return response()->json($e, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        return response()->json($order, JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::find($id);
        if(!$order) {
            return response()->json('Order Not Found', JsonResponse::HTTP_NO_CONTENT);
        }
        return response()->json($order, JsonResponse::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::find($id);
        if(!$order) {
            return response()->json('Order Not Found', JsonResponse::HTTP_NO_CONTENT);
        }
        // $request->merge(['id_client'=> Auth::user()->id]);
        try{
            $order->update($request->only([
                'order_date',
                'status',
                'id_client',
                'shipping',
                'total'
            ]));
        } catch (Exception $e) {
            return response()->json($e, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        return response()->json('Order Updated', JsonResponse::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::find($id);
        if(!$order) {
            return response()->json('Order Not Found', JsonResponse::HTTP_NO_CONTENT);
        }
        $order->delete();
        return response()->json('deleted', JsonResponse::HTTP_OK);
    }
}
