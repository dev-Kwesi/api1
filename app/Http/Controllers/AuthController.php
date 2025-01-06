<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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

    //login function
    public function login(Request $request){

        $validated= Validator::make($request->all(),[

            'email'=>'required|string|email|max:255',
            'password'=>'required|string|min:6'

        ]);

        if($validated->fails()){
            return response()->json($validated->errors(),422);
        }

        $credentials = [
            'email'=>$request->email,
            'password'=>$request->password
        ];

        try {

            if(!auth()->attempt($credentials)){
                return response()->json(['error'=>'Invalide credetials'],403);
            }

            $user = User::where('email',$request->email)->firstorFail();

            $token = $user->createToken('auth_token')->plainTextToken;

            
        return response()->json([
            'access_token'=>$token,
            'user'=>$user,

        ],200);
            //code...
        } catch (\Throwable $th) {
            
            return response()->json(['error'=>$th->getMessage()],403);
        }

    }
    
    //logout function

    public function logout(Request $request){
    $request->user()->currentAccesstoken()->delete();

       return response()->json([
        
            'message'=> 'user has been logged out'

        ],200);

}
}
