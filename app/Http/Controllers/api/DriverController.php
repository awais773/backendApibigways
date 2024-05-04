<?php

namespace App\Http\Controllers\api;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Token;

class DriverController extends Controller
{

    public function index()
    {
        $data = Driver::get();
        if (is_null($data)) {
            return response()->json('data not found',);
        }
        return response()->json([
            'success' => true,
            'message' => 'All Data susccessfull',
            'data' => $data,
        ]);
    }


    // public function driverLogin(Request $request)
    // {
    // $customer = Driver::where('email', $request->email)->first();
    // if (!$customer) {
    //     return response()->json(['error' => 'email not found'], 400);
    // }
    //     if($customer = Driver::where('email', $request->email)->first())
    //       {
    //         auth()->login($customer);
    //          $token = auth()->driver()->createToken('Token')->accessToken;
    //          return response()->json([
    //             'success'=>true,
    //             'message'=>'login successfull',
    //             'data'=>$data = ([
    //               'user'=> Driver::find(Auth::id()),
    //               'token'=>$token,
    //             ])

    //              ],200);
    //       }
    //       else{
    //         return response()->json([
    //             'success'=>false,
    //             'message'=>'please register'],401);
    //       }
    // }



    public function show($id)
    {
        $data = Driver::with('vehicle')->where('id',$id)->first();

        // foreach ($data as $Driver) {
        //     $Driver->image = json_decode($Driver->image); // Decode the JSON-encoded location string
        // }

        return response()->json([
            'success' => true,
            'message' => 'All Data successful',
            'data' => $data,
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'reg_no' => 'required|unique:vehicles',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                // 'message' => $validator->errors()->toJson()
                'message' => 'Registration Number already exist',

            ], 400);
        } {
            $user = Auth::user();
            $Driver = Driver::create($request->post());
            if ($file = $request->file('profile_picture')) {
                $video_name = md5(rand(1000, 10000));
                $ext = strtolower($file->getClientOriginalExtension());
                $video_full_name = $video_name . '.' . $ext;
                $upload_path = 'profilePicture/';
                $video_url = $upload_path . $video_full_name;
                $file->move($upload_path, $video_url);
                $Driver->profile_picture = $video_url;
            }
            $Driver->save();
            return response()->json([
                'success' => true,
                'message' => 'Driver Create successfull',
                'date' => $Driver,
            ], 200);
        }
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            // 'reg_no' => 'required|unique:vehicles',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                // 'message' => $validator->errors()->toJson()
                'message' => 'Registration Number already exist',

            ], 400);
        }
        $obj = Driver::find($id);
        if ($obj) {
            if (!empty($request->input('name'))) {
                $obj->name = $request->input('name');
            }
            if (!empty($request->input('postal_code'))) {
                $obj->postal_code = $request->input('postal_code');
            }
            if (!empty($request->input('city'))) {
                $obj->city = $request->input('city');
            }
            if (!empty($request->input('email'))) {
                $obj->email = $request->input('email');
            }
            if (!empty($request->input('last_name'))) {
                $obj->last_name = $request->input('last_name');
            }
            if (!empty($request->input('joining_date'))) {
                $obj->joining_date = $request->input('joining_date');
            }
            if (!empty($request->input('mobile'))) {
                $obj->mobile = $request->input('mobile');
            }
            if (!empty($request->input('gender'))) {
                $obj->gender = $request->input('gender');
            }

            if (!empty($request->input('address'))) {
                $obj->address = $request->input('address');
            }

            if (!empty($request->input('date_of_birth'))) {
                $obj->date_of_birth = $request->input('date_of_birth');
            }

            if (!empty($request->input('salary'))) {
                $obj->salary = $request->input('salary');
            }

            if (!empty($request->input('vehicle_id'))) {
                $obj->vehicle_id = $request->input('vehicle_id');
            }

            if (!empty($request->input('careTaker_id'))) {
                $obj->careTaker_id = $request->input('careTaker_id');
            }

            if (!empty($request->input('password'))) {
                $obj->password = $request->input('password');
            }
            if ($file = $request->file('profile_picture')) {
                $video_name = md5(rand(1000, 10000));
                $ext = strtolower($file->getClientOriginalExtension());
                $video_full_name = $video_name . '.' . $ext;
                $upload_path = 'profilePicture/';
                $video_url = $upload_path . $video_full_name;
                $file->move($upload_path, $video_url);
                $obj->profile_picture = $video_url;
            }
            $obj->save();
        return response()->json([
            'success' => true,
            'message' => 'Driver is updated successfully',
            'data' => $obj,
        ]);
    }
}

    public function destroy($id)
    {
        $program = Driver::find($id);
        if (!empty($program)) {
            $program->delete();
            return response()->json([
                'success' => true,
                'message' => ' delete successfuly',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'something wrong try again ',
            ]);
        }
    }
    public function driverLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $driver = Driver::where('email', $request->email)->first();

        if (!$driver) {
            return response()->json(['error' => 'Email not found'], 400);
        }

        // Check if the provided password matches the hashed password
        if ($request->password === $driver->password) {
            Auth::guard('driver')->login($driver);
            $token = $driver->createToken('Token')->accessToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => $driver,
                    'token' => $token,
                ]
            ], 200);
        } else {
            // If authentication fails, return an error response
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }
    }
}
