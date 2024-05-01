<?php

namespace App\Http\Controllers\api;

use App\Models\Driver;
use App\Models\Student;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use App\Models\DriverAttendance;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CareTakerAttendance;

use function Laravel\Prompts\select;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{


    public function index()
    {
        $data = DriverAttendance::with('driver:id,name')->select(
            'driver_id',
            DB::raw('SUM(CASE WHEN attendance = "Present" THEN 1 ELSE 0 END) as total_present'),
            DB::raw('SUM(CASE WHEN attendance = "Absent" THEN 1 ELSE 0 END) as total_absent'),
        )
            ->groupBy('driver_id')
            ->get();

        if ($data->isEmpty()) {
            return response()->json('data not found');
        }

        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'data' => $data,
        ]);
    }

    public function show($id)
    {
        $data = DriverAttendance::with('driver:id,name')->where('driver_id',$id)->get();
        $data->transform(function ($item) {
            $item->date = date('Y-m-d', strtotime($item->created_at)); // Extract date
            $item->time = date('H:i:s', strtotime($item->created_at)); // Extract time
            unset($item->created_at); // Remove the original created_at field
            return $item;
        });
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
            $Driver = DriverAttendance::create($request->post());
            $Driver->save();
            return response()->json([
                'success' => true,
                'message' => 'Driver Create successfull',
                'date' => $Driver,
            ], 200);
        }
    }


    public function destroy($id)
    {
        $program = DriverAttendance::find($id);
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


    public function careTakerAttendance()
    {
        $data = CareTakerAttendance::with('careTaker:id,name')->select(
            'careTaker_id',
            DB::raw('SUM(CASE WHEN attendance = "Present" THEN 1 ELSE 0 END) as total_present'),
            DB::raw('SUM(CASE WHEN attendance = "Absent" THEN 1 ELSE 0 END) as total_absent'),
        )
            ->groupBy('careTaker_id')
            ->get();

        if ($data->isEmpty()) {
            return response()->json('data not found');
        }

        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'data' => $data,
        ]);
    }

    public function careTakerAttenShow($id)
    {
        $data = CareTakerAttendance::with('careTaker:id,name')->where('careTaker_id',$id)->get();
        $data->transform(function ($item) {
            $item->date = date('Y-m-d', strtotime($item->created_at)); // Extract date
            $item->time = date('H:i:s', strtotime($item->created_at)); // Extract time
            unset($item->created_at); // Remove the original created_at field
            return $item;
        });
        return response()->json([
            'success' => true,
            'message' => 'All Data successful',
            'data' => $data,
        ]);
    }

    public function careTakerAttenStore(Request $request)
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
            $Driver = CareTakerAttendance::create($request->post());
            $Driver->save();
            return response()->json([
                'success' => true,
                'message' => 'Driver Create successfull',
                'date' => $Driver,
            ], 200);
        }
    }

}
