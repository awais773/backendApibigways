<?php

namespace App\Http\Controllers\api;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{

    public function index()
    {
        $data = Student::where('parent_id', Auth::user()->id)->with('parent')->get();
        if (is_null($data)) {
            return response()->json('data not found',);
        }
        return response()->json([
            'success' => true,
            'message' => 'All Data susccessfull',
            'data' => $data,
        ]);
    }

    public function studentGet($id)
    {
        $data = Student::where('parent_id', $id)->get();
        if (is_null($data)) {
            return response()->json('data not found',);
        }
        return response()->json([
            'success' => true,
            'message' => 'All Data susccessfull',
            'data' => $data,
        ]);
    }

    public function vehicleIndex($type)
    {
        $data = Student::where('vehicle_type', $type)->latest()->get();
        // foreach ($data as $Driver) {
        //     $Driver->image = json_decode($Driver->image); // Decode the JSON-encoded location string
        // }
        if (is_null($data)) {
            return response()->json('data not found',);
        }
        return response()->json([
            'success' => true,
            'message' => 'All Data susccessfull',
            'data' => $data,
        ]);
    }



    public function show($id)
    {
        $data = Student::find($id);
    
        // foreach ($data as $Driver) {
        //     $Driver->image = json_decode($Driver->image); // Decode the JSON-encoded location string
        // }
    
        return response()->json([
            'success' => true,
            'message' => 'All Data successful',
            'data' => $data,
        ]);
    }
    
    public function notAssign()
    {
        $data = Vehicle::with('driver:id,vehicle_id,name,last_name','company' )
            ->whereDoesntHave('driver', function ($query) {
                $query->whereNotNull('vehicle_id');
            })
            ->get();

        if ($data->isEmpty()) {
            return response()->json('No unassigned vehicles found.');
        }

        return response()->json([
            'success' => true,
            'message' => 'All Data successfully',
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
            // $user = Auth::user();
            $Student = Student::create($request->post());
            if ($file = $request->file('image')) {
                $video_name = md5(rand(1000, 10000));
                $ext = strtolower($file->getClientOriginalExtension());
                $video_full_name = $video_name . '.' . $ext;
                $upload_path = 'Student/';
                $video_url = $upload_path . $video_full_name;
                $file->move($upload_path, $video_url);
                $Student->image = $video_url;
            }
            // $Student->parent_id = $user->id;
            $Student->save();
            return response()->json([
                'success' => true,
                'message' => 'Student Create successfull',
                'date' => $Student,
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
        $obj = Student::find($id);
        if ($obj) {
            if (!empty($request->input('student_name'))) {
                $obj->student_name = $request->input('student_name');
            }
            if (!empty($request->input('school_name'))) {
                $obj->school_name = $request->input('school_name');
            }
            if (!empty($request->input('notes'))) {
                $obj->notes = $request->input('notes');
            }
            if (!empty($request->input('pickup_time'))) {
                $obj->pickup_time = $request->input('pickup_time');
            }
            if (!empty($request->input('drop_time'))) {
                $obj->drop_time = $request->input('drop_time');
            }
            if (!empty($request->input('parent_id'))) {
                $obj->parent_id = $request->input('parent_id');
            }
           
            if ($file = $request->file('image')) {
                $video_name = md5(rand(1000, 10000));
                $ext = strtolower($file->getClientOriginalExtension());
                $video_full_name = $video_name . '.' . $ext;
                $upload_path = 'Student/';
                $video_url = $upload_path . $video_full_name;
                $file->move($upload_path, $video_url);
                $obj->image = $video_url;
            }
            $obj->save();
        return response()->json([
            'success' => true,
            'message' => 'Student is updated successfully',
            'data' => $obj,
        ]);
    }
}

    public function destroy($id)
    {
        $program = Student::find($id);
        if (!empty($program)) {
            $program->delete();
            return response()->json([
                'success' => 'True',
                'message' => ' delete successfuly',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'something wrong try again ',
            ]);
        }
    }
}
