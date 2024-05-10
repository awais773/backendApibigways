<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Expense::all();
        return response()->json([
            'success' => true,
            'message' => 'All Data susccessfull',
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
                    'message' => 'Expense Create successfull',
                    'date' => $data,
                ], 200);
            }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = Expense::find($id);
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
