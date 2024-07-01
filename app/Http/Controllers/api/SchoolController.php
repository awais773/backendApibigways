<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = School::latest()->get();
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
            // 'email' => 'email|unique:schools,email',
         ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create school.',
                'errors' => $validator->errors(),
            ], 400);
        }

        $data = School::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'School created successfully',
            'data' => $data,
        ], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = School::find($id);
        return response()->json([
            'success' => true,
            'message' => 'School retrieved successfully',
            'data' => $data,
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = School::find($id);

        if ($data) {
            if (!empty($request->input('name'))) {
                $data->name = $request->input('name');
            }
            if (!empty($request->input('address'))) {
                $data->address = $request->input('address');
            }
            if (!empty($request->input('phone'))) {
                $data->phone = $request->input('phone');
            }
            if (!empty($request->input('email '))) {
                $data->email = $request->input('email');
            }
            if (!empty($request->input('branch_name'))) {
                $data->branch_name = $request->input('branch_name');
            }
            if (!empty($request->input('drop_longitude'))) {
                $data->drop_longitude = $request->input('drop_longitude');
            }
            if (!empty($request->input('drop_latitude'))) {
                $data->drop_latitude = $request->input('drop_latitude');
            }
            if (!empty($request->input('amount'))) {
                $data->amount = $request->input('amount');
            }
            $data->save();

            return response()->json([
                'success' => true,
                'message' => 'School is updated successfully',
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'School not found.',
            ]);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = School::find($id);
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
}
