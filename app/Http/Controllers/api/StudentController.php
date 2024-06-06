<?php

namespace App\Http\Controllers\api;

use App\Models\Payment;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Driver;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index()
    {
        $data = Student::where('parent_id', Auth::id())
            ->with(['vehicle.driver:vehicle_id,mobile'])
            ->latest()->get();

        if ($data->isEmpty()) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'All Data successful',
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



    public function Studentshow($id)
    {
        // $data = Student::find($id);
        $data = Student::with('vehicle.driver:vehicle_id,mobile')
        ->latest()->get();

        // foreach ($data as $Driver) {
        //     $Driver->image = json_decode($Driver->image); // Decode the JSON-encoded location string
        // }

        return response()->json([
            'success' => true,
            'message' => 'All Data successful',
            'data' => $data,
        ]);
    }
    public function show($id)
    {
        $data = Student::with('zone','vehicle.driver')->find($id);
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
            if (!empty($request->input('vehicle_id'))) {
                $obj->vehicle_id = $request->input('vehicle_id');
            }
            if (!empty($request->input('zone_id'))) {
                $obj->zone_id = $request->input('zone_id');
            }
            if (!empty($request->input('amount'))) {
                $obj->amount = $request->input('amount');
            }
            if (!empty($request->input('payments_status'))) {
                $obj->payments_status = $request->input('payments_status');
            }
            if (!empty($request->input('signed_status'))) {
                $obj->signed_status = $request->input('signed_status');
            }
            if (!empty($request->input('school_id'))) {
                $obj->school_id = $request->input('school_id');
            }
            if (!empty($request->input('distance'))) {
                $obj->distance = $request->input('distance');
            }
            if (!empty($request->input('student_pickup_name'))) {
                $obj->student_pickup_name = $request->input('student_pickup_name');
            }
            if (!empty($request->input('student_pickup_latidute'))) {
                $obj->student_pickup_latidute = $request->input('student_pickup_latidute');
            }
            if (!empty($request->input('student_pickup_longitude'))) {
                $obj->student_pickup_longitude = $request->input('student_pickup_longitude');
            }
            if (!empty($request->input('type'))) {
                $obj->type = $request->input('type');
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
    public function studentPickList(Request $request , $id)
    {
        {
            $vehicleId = $id;
            // $vehicleId = Auth::user()->$id;

            $driver = Driver::whereHas('vehicle', function ($query) use ($vehicleId) {
                $query->where('id', $vehicleId);
            })->with('vehicle')->first();

            $students = Student::whereHas('vehicle', function ($query) use ($vehicleId) {
                $query->where('id', $vehicleId);
            })
            ->with('parent:id,name,phone_number')
            ->get();

            $data = [
                'vehicle' => $driver->vehicle,
                'students' => $students,
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
    }

}



    public function stripePost(Request $request)
    {
        try {
            // Set Stripe API secret key
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            $intent = \Stripe\PaymentIntent::create([
            'amount' => $request->amount,
            'currency' => 'usd',
              ]);
            // Return success response if the charge was successful
            return response([
                'success' => true,
                'message' => 'Payment Successful',
                'data' => $intent->id,
                // 'data' => $intent->id
            ], 201);
        } catch (\Exception $e) {
            // Return error response if an exception occurs during payment processing
            return response([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }



    public function checkPayment(Request $req,)
    {
        $id = $req->student_id;
        $userID = $req->user()->id;
        $validator = Validator::make($req->all(), [
            // 'name' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        // Retrieve the Payment Intent using the provided client_secret
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $paymentIntent = \Stripe\PaymentIntent::retrieve($req->id);
        if ($paymentIntent->status === 'succeeded') {
            // Payment succeeded, update the invoice status
              $payment = Student::find($id);
              $payment->payments_status = 'PAID';
              $payment->save();
              $Payments = Payment::create([
                'student_id' => $id,
                'parent_id' => $userID,
              ]);
              $Payments->save();
            return response()->json([
                'success' => true,
                'message' => 'Payment Successful',
                // 'data' => $payment
            ], 200);
        } else {
            // Payment incomplete or failed, show a message
            return response()->json([
                'success' => false,
                'message' => 'Payment Incomplete'
            ], 400);
        }
    }



    public function PaymentHistroy()
    {
        $userID = Auth::user()->id;
        $data = Payment::with('student',)->where($userID,'parent_id')->latest()->get();
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

//  public function ManuallyAdd(Request $req){
//     $id = $req->student_id;
//     $userID = $req->user()->id;
//     $validator = Validator::make($req->all(), [
//         // 'name' => 'required|string|max:255',
//     ]);
//     if ($validator->fails()) {
//         return response()->json($validator->errors());
//     }
//             $payment = Student::find($id);
//             $payment->payments_status = 'PENDING';
//             if ($file = $req->file('image')) {
//                 $video_name = md5(rand(1000, 10000));
//                 $ext = strtolower($file->getClientOriginalExtension());
//                 $video_full_name = $video_name . '.' . $ext;
//                 $upload_path = 'Student/';
//                 $video_url = $upload_path . $video_full_name;
//                 $file->move($upload_path, $video_url);
//                 $payment->payments_image = $video_url;
//             }
//             $payment->save();
//             // $Payments = Payment::create($req->post());
//             $Payments = Payment::create([
//                 'student_id' => $id,
//                 'parent_id' => $userID,
//               ]);
//             if ($file = $req->file('image')) {
//                 $video_name = md5(rand(1000, 10000));
//                 $ext = strtolower($file->getClientOriginalExtension());
//                 $video_full_name = $video_name . '.' . $ext;
//                 $upload_path = 'Payments/';
//                 $video_url = $upload_path . $video_full_name;
//                 $file->move($upload_path, $video_url);
//                 $Payments->image = $video_url;
//             }
//             $Payments->save();
//            return response()->json([
//             'success' => true,
//             'message' => 'Payment Successful',
//             // 'data' => $payment
//         ], 200);
//     }

public function ManuallyAdd(Request $req) {
    $id = $req->student_id;
    $userID = $req->user()->id;

    $validator = Validator::make($req->all(), [
        // 'name' => 'required|string|max:255',
    ]);
    if ($validator->fails())
     {
        return response()->json($validator->errors());
    }
    $payment = Student::find($id);
    $payment->payments_status = 'PENDING';
    try {
        if ($file = $req->file('image')) {
            $video_name = md5(rand(1000, 10000));
            $ext = strtolower($file->getClientOriginalExtension());
            $video_full_name = $video_name . '.' . $ext;
            $upload_path = 'Payments/';
            $video_url = $upload_path . $video_full_name;
            if (!file_exists($upload_path)) {
                mkdir($upload_path, 0777, true);
            }
            $file->move($upload_path, $video_full_name);
            $payment->payments_image = $video_url;
        }
    } catch (FileException $e) {
        return response()->json([
            'success' => false,
            'message' => 'File upload error: ' . $e->getMessage(),
        ], 500);
    }
    $payment->save();

    $Payments = Payment::create([
        'student_id' => $id,
        'parent_id' => $userID,
    ]);
    if (!empty($payment->payments_image)) {
        $Payments->image = $payment->payments_image;
    }
    $Payments->save();
    return response()->json([
        'success' => true,
        'message' => 'Payment Successful',
    ], 200);
}
}



