<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\CareTaker;
use App\Models\Driver;
use App\Models\Student;
use App\Models\DriverAttendance;
use App\Models\StudentAttendance;
use Carbon\Carbon;
use App\Mail\BigwaysMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use function Laravel\Prompts\password;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{

    public function index()
    {
        $data = User::where('type', 'parents')->with('vehicle:id,name,vehicle_type')->latest()->get();
        // foreach ($data as $Driver) {
        //     $Driver->image = json_decode($Driver->image); // Decode the JSON-encoded location string
        // }
        if (is_null($data)) {
            return response()->json('data not found',);
        }
        $data->transform(function ($item) {
            $item->date = date('Y-m-d', strtotime($item->created_at)); // Extract date
            $item->time = date('H:i:s', strtotime($item->created_at)); // Extract time from created_at
            unset($item->created_at);
            return $item;
        });
        return response()->json([
            'success' => true,
            'message' => 'All Data susccessfull',
            'data' => $data,
        ]);
    }

    public function approved()
    {
        $data = User::where('type', 'parents')->where('status', 'Approved')->with('vehicle:id,name,vehicle_type')->latest()->get();
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

    public function reguestedDataGet()
    {
        $data = User::where('type', 'parents')->with('vehicle:id,name,vehicle_type')->latest()->get();
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

    public function parentGet()
    {
        $data = User::where( Auth::user()->id, 'id')->with('vehicle')->latest()->get();
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
        $data = User::with('vehicle')->where('id',$id)->first();
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
            'email' => 'required|string|email|max:255|unique:users',
            // 'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                // 'message' => $validator->errors()->toJson()
                'message' => 'Email already exist',

            ], 400);
        }
        $pass  = 12345678;
        $passwordHash = Hash::make($pass);
        $requestData = $request->post();
        $requestData['password'] = $passwordHash;
        $user = User::create($requestData);
        $user->save();
        $token = $user->createToken('Token')->accessToken;
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'somthin Wrong',], 422);
        }
        return response()->json([
            'success' => true,
            'message' => 'register successfull',
            'data' => $data = ([
                'token' => $token,
                'user' => $user
            ])

        ], 200);
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
        $obj = User::find($id);
        if ($obj) {
            if (!empty($request->input('status'))) {
                $obj->status = $request->input('status');
            }
            $obj->save();
            Mail::to($obj->email)->send(new BigwaysMail($obj));
        }
        return response()->json([
            'success' => true,
            'message' => 'status is updated successfully',
            'data' => $obj,
        ]);
    }


    public function updateReguest(Request $request, $id)
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
        $obj = User::find($id);
        if ($obj) {
            if (!empty($request->input('status'))) {
                $obj->status = $request->input('status');
            }
            if (!empty($request->input('name'))) {
                $obj->name = $request->input('name');
            }
             if (!empty($request->input('amount'))) {
                $obj->amount = $request->input('amount');
            }
            if (!empty($request->input('pickup_location'))) {
                $obj->pickup_location = $request->input('pickup_location');
            }
            if (!empty($request->input('drop_location'))) {
                $obj->drop_location = $request->input('drop_location');
            }
            if (!empty($request->input('total_students'))) {
                $obj->total_students = $request->input('total_students');
            }
            if (!empty($request->input('phone_number'))) {
                $obj->phone_number = $request->input('phone_number');
            }
            if (!empty($request->input('country'))) {
                $obj->country = $request->input('country');
            }
            if (!empty($request->input('city'))) {
                $obj->city = $request->input('city');
            }
            if (!empty($request->input('pickup_latitude'))) {
                $obj->pickup_latitude = $request->input('pickup_latitude');
            }

            if (!empty($request->input('pickup_longitude'))) {
                $obj->pickup_longitude = $request->input('pickup_longitude');
            }

            if (!empty($request->input('drop_latitude'))) {
                $obj->drop_latitude = $request->input('drop_latitude');
            }

            if (!empty($request->input('drop_longitude'))) {
                $obj->drop_longitude = $request->input('drop_longitude');
            }

            if (!empty($request->input('vehicle_id'))) {
                $obj->vehicle_id = $request->input('vehicle_id');
            }
            if (!empty($request->input('email'))) {
                $obj->status = $request->input('email');
            }
            $obj->save();
        }
        return response()->json([
            'success' => true,
            'message' => 'Data is updated successfully',
            'data' => $obj,
        ]);
    }


    public function destroy($id)
    {
        $program = User::find($id);
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
    public function dashboard()
    {
        $totalCareTakers = CareTaker::count();
        $totalVehicles = Vehicle::count();
        $totalDrivers = Driver::count();
        $totalStudents = Student::count();
        $totalParents = User::where('type', 'parents')->where('status', 'Approved')->count();
        $totalPendingRequests = User::where('type', 'parents')->where('status', 'Pending')->count();

        $todayDriverAttendance = DriverAttendance::whereDate('created_at', Carbon::today())->count();

        $totalDriverPresentToday = DriverAttendance::whereDate('created_at', Carbon::today())
                                    ->where('attendance', 'Present')->count();

        $totalDriverAbsentToday = DriverAttendance::whereDate('created_at', Carbon::today())
                                    ->where('attendance', 'Absent')->count();

        $todayStudentAttendance = StudentAttendance::whereDate('created_at', Carbon::today())->count();

        $totalStudentPresentToday = StudentAttendance::whereDate('created_at', Carbon::today())
                                    ->where('attendance', 'Present')->count();

        $totalStudentAbsentToday = StudentAttendance::whereDate('created_at', Carbon::today())
                                    ->where('attendance', 'Absent')->count();

        $data = [
            'total_careTakers' => $totalCareTakers,
            'total_vehicles' => $totalVehicles,
            'total_drivers' => $totalDrivers,
            'total_students' => $totalStudents,
            'total_parents' => $totalParents,
            'total_pending_requests' => $totalPendingRequests,
            'today_drivers_attendance' => $todayDriverAttendance,
            'total_drivers_present_today' => $totalDriverPresentToday,
            'total_drivers_absent_today' => $totalDriverAbsentToday,
            'today_students_attendance' => $todayStudentAttendance,
            'total_students_present_today' => $totalStudentPresentToday,
            'total_students_absent_today' => $totalStudentAbsentToday,
        ];

        return response()->json([
            'success' => true,
            'total_counts' => $data,
        ]);
    }
    public function getMonthlyPendingRequests()
    {
        $monthlyRequests = DB::table(DB::raw('(SELECT 1 as month UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12) as months'))
        ->leftJoin('users', function($join) {
            $join->on(DB::raw('MONTH(users.created_at)'), '=', 'months.month')
                ->where('users.type', 'parents')
                ->where('users.status', 'Pending');
        })
        ->select(
            'months.month',
            DB::raw('COALESCE(COUNT(users.id), 0) as pending_requests')
        )
        ->groupBy('months.month')
        ->get();

    return response()->json($monthlyRequests);
    }
    public function getMonthlyApprovedRequests()
{
    $monthlyRequests = DB::table(DB::raw('(SELECT 1 as month UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12) as months'))
        ->leftJoin('users', function($join) {
            $join->on(DB::raw('MONTH(users.created_at)'), '=', 'months.month')
            ->where('users.type', 'parents')
            ->where('users.status', 'Approved');
        })
        ->select(
            'months.month',
            DB::raw('COALESCE(COUNT(users.id), 0) as approved_requests')
        )
        ->groupBy('months.month')
        ->get();

    return response()->json($monthlyRequests);
}
}
