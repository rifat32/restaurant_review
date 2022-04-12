<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // ##################################################
    // This method is to register a user
    // ##################################################
    public function register(AuthRegisterRequest $request)
    {


        $request['password'] = Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $user =  User::create($request->toArray());
        $user->token = $user->createToken('Laravel Password Grant Client')->accessToken;

        return response(["message" => "You have successfully registered",  $user], 200);
    }
    // ##################################################
    // This method is to login a user
    // ##################################################
    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($loginData)) {
            return response(['message' => 'Invalid Credentials'], 401);
        }


        $user = auth()->user();
        $user->token  = auth()->user()->createToken('authToken')->accessToken;


        return response()->json($user, 200);
    }
    // ##################################################
    // This method is to check pin
    // ##################################################
    public function checkPin($id, Request $request)
    {
        $pinData =    $request->validate([
            'pin' => 'required'
        ]);
        $user =  User::where(["id" => $id])->first();
        if (!$user) {
            return response()->json([
                "message" => "Invalid Pin"
            ], 400);
        }

        if (!Hash::check($pinData["pin"], $user->pin)) {
            return response()->json([
                "message" => "Invalid Pin"
            ], 400);
        }

        return response()->json([
            "message" => "Pin Matched"
        ], 400);
    }
    // ##################################################
    // This method is to get user with restaurant
    // ##################################################
    public function getUserWithRestaurent(Request $request)
    {
        // @@@@@@@@@@ should connect with restaurent
        $user = auth()->user();
        return response()->json($user, 200);
    }
}
