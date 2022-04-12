<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class OwnerController extends Controller
{
    // ##################################################
    // This method is to store user
    // ##################################################
    public function createUser(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'email|required|unique:users,email',
            'password' => 'required|string|min:6',
            'first_Name' => 'required',
            'phone' => 'nullable',
            'last_Name' => 'nullable'
        ]);

        $validatedData = $validator->validated();

        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['remember_token'] = Str::random(10);
        $user =  User::create($validatedData);
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $data["user"] = $user;
        return response(["ok" => true, "message" => "You have successfully registered", "data" => $data, "token" => $token], 200);
    }
    // ##################################################
    // This method is to store super admin
    // ##################################################
    public function createsuperAdmin(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'email' => 'email|required|unique:users,email',
            'password' => 'required|string|min:6',
            'first_Name' => 'required',
            'phone' => 'nullable',
            'last_Name' => 'nullable'
        ]);

        $validatedData = $validator->validated();

        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['remember_token'] = Str::random(10);
        $user =  User::create($validatedData);
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $data["user"] = $user;
        if (!Role::where(['name' => 'superadmin'])->exists()) {
            Role::create(['name' => 'superadmin']);
        }
        $user->assignRole('superadmin');
        return response(["ok" => true, "message" => "You have successfully registered", "data" => $data, "token" => $token], 200);
    }
    // ##################################################
    // This method is to get role
    // ##################################################
    public function getRole(Request $request)
    {


        return response()->json($request->user()->getRoleNames());
    }
    // ##################################################
    // This method is to store user2
    // ##################################################
    public function createUser2(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'email|required|unique:users,email',
            'password' => 'required|string|min:6',
            'first_Name' => 'required',
            'phone' => 'nullable',
        ]);

        $validatedData = $validator->validated();

        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['remember_token'] = Str::random(10);
        $user =  User::create($validatedData);
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $data["user"] = $user;
        return response(["ok" => true, "message" => "You have successfully registered", "data" => $data, "token" => $token], 200);
    }
    // ##################################################
    // This method is to store guest user
    // ##################################################
    public function createGuestUser(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'email|required|unique:users,email',
            'first_Name' => 'required',
            'phone' => 'nullable',
            'type' => 'nullable',
        ]);

        $validatedData = $validator->validated();
        // password is not need
        // $validatedData['password'] = Hash::make($request['password']);

        $validatedData['remember_token'] = Str::random(10);
        $user =  User::create($validatedData);
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $data["user"] = $user;
        return response(["ok" => true, "message" => "You have successfully registered", "data" => $data, "token" => $token], 200);
    }
    // ##################################################
    // This method is to store stalf
    // ##################################################
    public function createStaffUser($restaurantId, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'email|required|unique:users,email',
            'first_Name' => 'required',
            'phone' => 'nullable',
            'type' => 'nullable',
            'password' => 'string|nullable',
        ]);

        $validatedData = $validator->validated();

        if (array_key_exists('password', $validatedData)) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        $validatedData['remember_token'] = Str::random(10);
        $user =  User::create($validatedData);
        // $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $data["user"] = $user;



        // @@@@@@@@@@@@@@@@@@@@@@@@

        // insert into res_link (user_id,restaurantid)





        return response([
            "ok" => true, "message" => "Staff Added Successfully", "data" => $data,
            // "token" => $token
        ], 200);
    }
    // ##################################################
    // This method is to update pin
    // ##################################################
    public function updatePin($id, Request $request)
    {
        $validator = Validator::make($request->all(), [

            'pin' => 'required',

        ]);

        $validatedData = $validator->validated();
        User::where(["id" => $id])
            ->update([
                "pin" => $validatedData["pin"]
            ]);
        return response(["ok" => true, "message" => "Pin Updated Successfully."], 200);
    }

    // ##################################################
    // This method is to get user by id
    // ##################################################
    public function getOwnerById($id)
    {
        $data["user"] =   User::where(["id" => $id])->first();
        $data["ok"] = true;

        if (!$data["user"]) {
            return response(["message" => "No User Found"], 404);
        }
        return response($data, 200);
    }
    // ##################################################
    // This method is to get user not havhing restaurant
    // ##################################################
    public function getOwnerNotHaveRestaurent()
    {


        // @@@@@@@@@@
        // where not in restaurent select id

        $data["user"] =      USER::whereNotIn('id', [2, 3])->get();
        $data["ok"] = true;
        return response($data, 200);
    }
    // ##################################################
    // This method is to get user by phone number
    // ##################################################
    public function getOwnerByPhoneNumber($phoneNumber)
    {
        $data["user"] =   User::where(["phone" => $phoneNumber])->first();
        $data["ok"] = true;
        if (!$data["user"]) {
            return response(["message" => "No User Found"], 404);
        }
        return response($data, 200);
    }
    // ##################################################
    // This method is to update user
    // ##################################################
    public function updateUser($userId, Request $request)
    {

        $data["user"] =    tap(User::where(["id" => $userId]))->update($request->only(
            "first_Name",
            "last_Name",
            "phone",
            "Address",
        ))
            // ->with("somthing")

            ->first();


        if (!$data["user"]) {
            return response()->json(["message" => "No User Found"], 404);
        }


        $data["message"] = "user updates successfully";
        return response()->json($data, 200);
    }
    // ##################################################
    // This method is to update  user image
    // ##################################################
    public function updateImage($userId, Request $request)
    {
        $request->validate([

            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);



        $imageName = time() . '.' . $request->logo->extension();



        $request->logo->move(public_path('img/user'), $imageName);

        $imageName = "img/user/" . $imageName;

        $data["user"] =    tap(User::where(["id" => $userId]))->update([
            "image" => $imageName
        ])
            // ->with("somthing")

            ->first();


        if (!$data["user"]) {
            return response()->json(["message" => "No User Found"], 404);
        }


        $data["message"] = "image updates successfully";
        return response()->json($data, 200);
    }
}
