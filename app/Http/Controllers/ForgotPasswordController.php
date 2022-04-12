<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function store(Request $request)
    {

        // ##################################################
        // This method is to store token
        // ##################################################
        $query = User::where(["email" => $request->email]);

        if (!$query->exists()) {
            return response()->json(["message" => "no user found"], 404);
        }

        $token = Str::random(30);

        $query->update([
            "resetPasswordToken" => $token,
            "resetPasswordExpires" => Carbon::now()->subDays(-1)
        ]);
        return response()->json([
            "token" => $token
        ]);
    }
    // ##################################################
    // This method is to change password
    // ##################################################
    public function changePassword(Request $request)
    {

        $user = $request->user();
        if (!Hash::check($request->cpassword, $user->password)) {
            return response()->json([
                "message" => "Invalid password"
            ], 400);
        }
        $password = Hash::make($request->password);
        $user->update([
            "password" => $password
        ]);
        return response()->json([
            "message" => "password changed"
        ], 200);;
    }
    // ##################################################
    // This method is to change password by token
    // ##################################################
    public function changePasswordByToken($token, Request $request)
    {

        $user = User::where([
            "resetPasswordToken" => $token,
        ])
            ->where("resetPasswordExpires", ">", now())
            ->first();
        if (!$user) {
            return response()->json([
                "message" => "Invalid Token Or Token Expired"
            ], 400);
        }

        $password = Hash::make($request->password);
        $user->update([
            "password" => $password
        ]);
        return response()->json([
            "message" => "password changed"
        ], 200);;
    }
}
