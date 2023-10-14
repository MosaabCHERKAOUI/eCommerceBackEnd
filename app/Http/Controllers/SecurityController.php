<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SecurityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if(Auth::attempt($credentials)){ 
            $user = Auth::user(); 
            return response()->json([
                'message' => 'Login Success '.$user->email
            ],200);   
        } 
        else{ 
            return response()->json([
                'message' =>  'Email Or Password Incorrect'
            ]
            , 401);
        } 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($request->role == "admin") {
            $admin = User::create($request->all());
            $success['token'] =  $admin->createToken('myToken')->plainTextToken;
            Auth::login($admin);
            return response()->json(['token'=> $success], JsonResponse::HTTP_CREATED);
        } else if($request->role == "client") {
            $client = User::create($request->all());
            $success['token'] =  $client->createToken('myToken')->plainTextToken;
            Auth::login($client);
            return response()->json(['token'=> $success], JsonResponse::HTTP_CREATED);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
