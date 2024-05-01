<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\OtpVerificationMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required',
    ]);

    if($validator->fails()){
            return response()->json([
                'success'=>false,
                // 'message' => $validator->errors()->toJson()
                 'message'=> 'Email already exist',

            ], 400);
    }
        $user = User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password)
        ]);
       $token = $user->createToken('Token')->accessToken;
       if (!$user) {
        return response()->json([ 'success'=>false, 'message'=>'somthin Wrong',], 422);
      }
      return response()->json([ 'success'=>true,
      'message'=>'register successfull',
      'data'=>$data=([
        'token'=>$token,
        'user'=>$user
      ])

    ], 200);
  }


    public function login(Request $request)
    {
        $data=[
            'email'=>$request->email,
            'password'=>$request->password
        ];
          if(auth()->attempt($data))
          {
             $token = auth()->user()->createToken('Token')->accessToken;
             return response()->json([
                'success'=>true,
                'message'=>'login successfull',
                'data'=>$data = ([
                  'user'=> User::find(Auth::id()),
                  'token'=>$token,
                ])

                 ],200);
          }
          else{
            return response()->json([
                'success'=>false,
                'message'=>'please register'],401);
          }
    }


    public function tokenGet(Request $request)
    {
        $data=[
            'email'=>$request->email,
            'password'=>12345678,
        ];

          if(auth()->attempt($data))
          {
             $token = auth()->user()->createToken('Token')->accessToken;
             return response()->json([
                'success'=>true,
                'message'=>'token create successfull',
                'data'=>$data = ([
                  // 'user'=> User::find(Auth::id()),
                  'token'=>$token,
                ])

                 ],200);
          }
          else{
            return response()->json([
                'success'=>false,
                'message'=>'please register'],401);
          }
    }

    public function userinfo()
    {
        $user = auth()->user();
        return response()->json(['user'=>$user],200);

    }
}
