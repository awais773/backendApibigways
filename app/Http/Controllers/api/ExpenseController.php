<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Expense::with('vehicle', 'driver')->latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'All Data successfully',
            'data' => $data,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'amount' => 'required|numeric',
            'type' => 'required|string|in:others,fuel',
            ]);
            if ($validator->fails())
            {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to enter expense.',
                ], 400);
            }
            else{
                $data = Expense::create($request->post());
                if ($file = $request->file('image')) {
                    $video_name = md5(rand(1000, 10000));
                    $ext = strtolower($file->getClientOriginalExtension());
                    $video_full_name = $video_name . '.' . $ext;
                    $upload_path = 'Expense/';
                    $video_url = $upload_path . $video_full_name;
                    $file->move($upload_path, $video_url);
                    $data->image = $video_url;
                }
                $data->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Expense Create successfully',
                    'date' => $data,
                ], 200);
            }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = Expense::with('vehicle', 'driver')->find($id);
        return response()->json([
            'success' => true,
            'message' => 'All Data successful',
            'data' => $data,
        ]);
    }
    public function driverExpenseshow($id)
    {
        $data = Expense::with('vehicle')->where('driver_id',$id)->get();
        return response()->json([
            'success' => true,
            'message' => 'All Data successful',
            'data' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
        public function update(Request $request, $id)
        {
            $data = Expense::find($id);

            if ($data) {
                if (!empty($request->input('name'))) {
                    $data->name = $request->input('name');
                }
                if (!empty($request->input('amount'))) {
                    $data->amount = $request->input('amount');
                }
                if (!empty($request->input('type'))) {
                    $data->type = $request->input('type');
                }
                if (!empty($request->input('vehicle_id'))) {
                    $data->vehicle_id = $request->input('vehicle_id');
                }
                if (!empty($request->input('driver_id'))) {
                    $data->driver_id = $request->input('driver_id');
                }
                if (!empty($request->input('expense_status'))) {
                    $data->expense_status = $request->input('expense_status');
                }
                if ($file = $request->file('image')) {
                    $video_name = md5(rand(1000, 10000));
                    $ext = strtolower($file->getClientOriginalExtension());
                    $video_full_name = $video_name . '.' . $ext;
                    $upload_path = 'Expense/';
                    $video_url = $upload_path . $video_full_name;
                    $file->move($upload_path, $video_url);
                    $data->image = $video_url;
                }
                $data->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Expense is updated successfully',
                    'data' => $data,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Expense not found.',
                ]);
            }
        }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Expense::find($id);
        if (!empty($data)) {
            $data->delete();
            return response()->json([
                'success' => true,
                'message' => ' delete successfully',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'something wrong try again ',
            ]);
        }
    }
    public function earningReport()
{
    $data = User::where('type', 'parents')
        ->select('id', 'name','total_students','payments_status','proof_image')
        ->get();
    return response()->json([
        'success' => true,
        'message' => 'Earning report successfully',
        'data' => $data,
    ]);
}
}
