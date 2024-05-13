<?php

namespace App\Http\Controllers\api;

use App\Models\Driver;
use App\Models\Student;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use App\Models\DriverAttendance;
use App\Models\StudentAttendance;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CareTakerAttendance;
use Carbon\Carbon;

use function Laravel\Prompts\select;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{


    public function index()
{
    $startDate = now()->startOfMonth(); // Start of current month
    $endDate = now()->endOfMonth(); // End of current month

    $data = DriverAttendance::with(['driver:id,name,vehicle_id', 'driver.vehicle:id,name,vehicle_number'])
    ->select(
        'driver_id',
        // 'vehicles.name as vehicle_name',
        // 'vehicles.vehicle_number',
        DB::raw('SUM(CASE WHEN attendance = "Present" THEN 1 ELSE 0 END) as total_present'),
        DB::raw('SUM(CASE WHEN attendance = "Absent" THEN 1 ELSE 0 END) as total_absent'),
        DB::raw('CONCAT(vehicles.name, " - ", vehicles.vehicle_number) as details_vehicle')
    )
    ->leftJoin('drivers', 'driver_attendances.driver_id', '=', 'drivers.id')
    ->leftJoin('vehicles', 'drivers.vehicle_id', '=', 'vehicles.id')
    ->whereBetween('driver_attendances.created_at', [$startDate, $endDate])
    ->groupBy('driver_id', 'vehicles.name', 'vehicles.vehicle_number')
    ->get();

    if ($data->isEmpty()) {
        return response()->json('Data not found');
    }

    return response()->json([
        'success' => true,
        'message' => 'Data retrieved successfully',
        'data' => $data,
    ]);
}

    public function show(Request $request, $id)
{
    $selectedDate = $request->input('selected_date');
    $endedDate = $request->input('ended_date');

    if (!$selectedDate || !$endedDate) {
        $startDate = DriverAttendance::where('driver_id', $id)->oldest('created_at')->value('created_at');
        $endDate = now();
    } else {
        $selectedDate = Carbon::parse($selectedDate);
        $endedDate = Carbon::parse($endedDate);
        $startDate = $selectedDate->copy()->startOfMonth();
        $endDate = $endedDate->copy()->endOfMonth();
    }

    $data = DriverAttendance::with('driver:id,name')
        ->where('driver_id', $id)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->latest();

    $data->transform(function ($item) {
        $item->date = date('Y-m-d', strtotime($item->created_at)); // Extract date
        $item->check_in_time = date('H:i:s', strtotime($item->created_at)); // Extract check-in time from created_at
        $item->check_out_time = date('H:i:s', strtotime($item->updated_at)); // Extract check-out time from updated_at
        // $item->total_hours = Carbon::parse($item->created_at)->diffInSeconds($item->updated_at) / 3600; // Calculate total hours with minutes

        $totalSeconds = Carbon::parse($item->created_at)->diffInSeconds($item->updated_at);

        // Calculate total hours
        $totalHours = floor($totalSeconds / 3600);

        // Calculate remaining seconds after subtracting total hours
        $remainingSeconds = $totalSeconds % 3600;

        // Calculate total minutes
        $totalMinutes = floor($remainingSeconds / 60);

        // Calculate remaining seconds after subtracting total minutes
        $totalSeconds = $remainingSeconds % 60;
        $item->total_hours = $totalHours . ':' . $totalMinutes . ':' . $totalSeconds . '';
        unset($item->created_at);
        unset($item->updated_at);

        return $item;
    });
    return $data;
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
                'message' => 'Email already exist',

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

    public function careTakerAttenShow(Request $request, $id)
    {
        $selectedDate = $request->input('selected_date');
        $endedDate = $request->input('ended_date');
        // If no date is selected, default to the current week
        if (!$selectedDate || !$endedDate) {
            $startDate = now()->startOfWeek(); // Start of current week
            $endDate = now()->endOfWeek(); // End of current week
        } else {
            $selectedDate = Carbon::parse($selectedDate);
            $endedDate = Carbon::parse($endedDate);
            $startDate = $selectedDate->copy()->startOfMonth();
            $endDate = $endedDate->copy()->endOfMonth();
        }
        $data = CareTakerAttendance::with('careTaker:id,name')->where('careTaker_id',$id)
        ->whereBetween('created_at', [$startDate, $endDate])->latest()
        ->get();
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
                'message' => 'Email already exist',

            ], 400);
        } {
            $Driver = CareTakerAttendance::create($request->post());
            $Driver->save();
            return response()->json([
                'success' => true,
                'message' => 'Caretaker Create successfull',
                'date' => $Driver,
            ], 200);
        }
    }

    public function studentAttendance()
    {
        $data = StudentAttendance::with('student:id,student_name')->select(
            'student_id' ,
            DB::raw('SUM(CASE WHEN attendance = "Present" THEN 1 ELSE 0 END) as total_present'),
            DB::raw('SUM(CASE WHEN attendance = "Absent" THEN 1 ELSE 0 END) as total_absent'),
        )
            ->groupBy('student_id')
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
    public function studentAttenShow(Request $request, $id)
    {
        $search = $request->input('search');
        $selectedDate = $request->input('selected_date');
        $endedDate = $request->input('ended_date');

        // If no date is selected, default to the current week
        if (!$selectedDate || !$endedDate) {
            $startDate = now()->startOfWeek(); // Start of current week
            $endDate = now()->endOfWeek(); // End of current week
        } else {
            $selectedDate = Carbon::parse($selectedDate);
            $endedDate = Carbon::parse($endedDate);
            $startDate = $selectedDate->copy()->startOfMonth();
            $endDate = $endedDate->copy()->endOfMonth();
        }

        $query = StudentAttendance::with('student:id,student_name','vehicle:id,name,vehicle_number')
        ->where('student_id', $id)
        ->whereBetween('created_at', [$startDate, $endDate]);

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('id', 'LIKE', "%$search%")
              ->orWhere('attendance', 'LIKE', "%$search%")
              ->orWhereDate('created_at', 'LIKE', "%$search%")
              ->orWhereHas('student', function ($q) use ($search) {
                  $q->where('id', 'LIKE', "%$search%")
                    ->orWhere('student_name', 'LIKE', "%$search%")
                    ->orWhere('school_name', 'LIKE', "%$search%");
              })
              ->orWhereHas('vehicle', function ($q) use ($search) {
                  $q->Where('name', 'LIKE', "%$search%")
                    ->orWhere('vehicle_number', 'LIKE', "%$search%");
              });
        });
    }
        $data = $query->latest()->paginate(10);
        $data->transform(function ($item) {
            $item->date = date('Y-m-d', strtotime($item->created_at)); // Extract date
            $item->time = date('H:i:s', strtotime($item->created_at)); // Extract time
            unset($item->created_at); // Remove the original created_at field
            return $item;
        });
        return response()->json([
            'success' => true,
            'message' => 'Attendance data retrieved successfully',
            'data' => $data,
        ]);
    }

    public function studentAttenStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'reg_no' => 'required|unique:vehicles',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                // 'message' => $validator->errors()->toJson()
                'message' => 'Email already exist',

            ], 400);
        } {
            $Student = studentAttendance::create($request->post());
            $Student->save();
            return response()->json([
                'success' => true,
                'message' => 'Student Attendance Create successfull',
                'date' => $Student,
            ], 200);
        }
    }

}
