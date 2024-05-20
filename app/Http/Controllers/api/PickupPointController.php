<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\PickupPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PickupPointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = PickupPoint::latest()->get();
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
            // 'title' => 'required|string',
         ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create school.',
                'errors' => $validator->errors(),
            ], 400);
        }

        $data = PickupPoint::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Pickup Point created successfully',
            'data' => $data,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = PickupPoint::find($id);
        return response()->json([
            'success' => true,
            'message' => 'Pickup Point retrieved successfully',
            'data' => $data,
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = PickupPoint::find($id);

        if ($data) {
            if (!empty($request->input('name'))) {
                $data->name = $request->input('name');
            }
            if (!empty($request->input('pickup_name'))) {
                $data->pickup_name = $request->input('pickup_name');
            }
            if (!empty($request->input('drop_name'))) {
                $data->drop_name = $request->input('drop_name');
            }
            if (!empty($request->input('pickup_longitude'))) {
                $data->pickup_longitude = $request->input('pickup_longitude');
            }
            if (!empty($request->input('pickup_latitude'))) {
                $data->pickup_latitude = $request->input('pickup_latitude');
            }
            if (!empty($request->input('drop_longitude'))) {
                $data->drop_longitude = $request->input('drop_longitude');
            }
            if (!empty($request->input('drop_latitude'))) {
                $data->drop_latitude = $request->input('drop_latitude');
            }
            $data->save();

            return response()->json([
                'success' => true,
                'message' => 'Pickup Point is updated successfully',
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
    public function destroy(string $id)
    {
        $data = PickupPoint::find($id);
        if (!empty($data)) {
            $data->delete();
            return response()->json([
                'success' => true,
                'message' => 'Pickup Point delete successfully',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'something wrong try again ',
            ]);
        }
    }
}
