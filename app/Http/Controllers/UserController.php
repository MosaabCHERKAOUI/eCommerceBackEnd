<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():JsonResponse
    {
        $clients = User::where('role', 'client')->get();
        if($clients->isEmpty()) {
            return response()->json('No clients were found', JsonResponse::HTTP_NOT_FOUND);
        }
        return response()->json($clients, JsonResponse::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request):JsonResponse
    {
        $client = User::where('email', $request->email)->get();
        if(!$client->isEmpty()) {
            return response()->json('This email already exists', JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        try {
            $this->validate($request, [
                'f_name'=> 'string',
                'l_name'=> 'string',
                'email'=> 'email',
                'info_address'=> 'string',
                'city'=> 'string',
                'country'=> 'string',
                'phone_number'=> 'integer',
                'password'=> 'required|string|min:8'
            ]);
        } catch(ValidationException $e) {
            return response()->json($e->errors(), JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        try {
            $client = User::create([
                'f_name'=> $request->input('f_name'),
                'l_name'=> $request->input('l_name'),
                'email'=> $request->input('email'),
                'info_address'=> $request->input('info_address'),
                'city'=> $request->input('city'),
                'country'=> $request->input('country'),
                'phone_number'=> $request->input('phone_number'),
                'password'=> $request->input('password'),
                'role'=> $request->input('role') ?? 'client'
            ]);
            $token = $client->createToken($client->f_name);
            return response()->json($token, JsonResponse::HTTP_CREATED);
        } catch(Exception $e) {
            return response()->json($e, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id):JsonResponse
    {
        $client = User::find($id);
        if(!$client) {
            return response()->json('User not found', JsonResponse::HTTP_NOT_FOUND);
        }
        return response()->json($client, JsonResponse::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id):JsonResponse
    {
        $client = User::find($id);
        if(!$client) {
            return response()->json("User Not Found", JsonResponse::HTTP_NOT_FOUND);
        } else {
            if($client->password == $request->password) {
                return response()->json('Old Password does not match new password');
            }
            try {
                $this->validate($request, [
                    'f_name'=> 'string',
                    'l_name'=> 'string',
                    'email'=> 'email',
                    'address'=> 'string|max:50',
                    'phone_number'=> 'digits_between:10,15',
                    'city'=> 'string',
                    'info_address'=> 'string',
                    'country'=> 'string',
                    'old_password'=> 'string',
                    'password'=> 'string'
                ]);
            } catch (ValidationException $e) {
                return response()->json($e->errors(), JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
            $client->fill($request->only([
                'f_name',
                'l_name',
                'email',
                'address',
                'phone_number',
                'password',
                'info_address',
                'country',
                'city'
            ]));
            $client->save();
            return response()->json(["User updated successfully", $client], JsonResponse::HTTP_OK);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id):JsonResponse
    {
        $client = User::find($id);
        if(!$client) {
            return response()->json('User not found', JsonResponse::HTTP_NOT_FOUND);
        }
        $client->delete();
        return response()->json($client, JsonResponse::HTTP_NO_CONTENT);
    }
}
