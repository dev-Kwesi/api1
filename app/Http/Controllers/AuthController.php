<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function register(Request $request){

        // $validated =$request->validate([

        //     'name'=>'required|string|max:255',
        //     'email'=>'required|string|email|unique:users',
        //     'password'=>'required|string|min:6|max:20|confirmed',

        // ]);

        $validated = Validator::make($request->all(),[

            
           'name'=>'required|string|max:255',
            'email'=>'required|string|email|unique:users',
           'password'=>'required|string|min:6|max:20|confirmed',

        ]);

        if($validated->fails()){
            return response()->json($validated->errors(),422);
        }

        $user = User::create([
'name'=>$request->name,
'email'=>$request->email,
'password'=>Hash::make($request->password)

        ]);

        $token =$user->createToken('auth_token')->plainTextToken;

        //return user

        return response()->json([
            'access_token'=>$token,
            'user'=>$user,

        ],200);

    }
}