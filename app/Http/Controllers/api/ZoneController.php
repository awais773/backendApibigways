<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $zones = Zone::with('pickup_points:id,title,pickup_name,drop_name')->latest()->get();

    //     foreach ($zones as $zone) {
    //         $zone->vehicles = $zone->vehicles;
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'All Data successfully retrieved',
    //         'data' => $zones,
    //     ]);
    // }
    public function index()
    {
        $zones = Zone::with('schools')
                     ->with('vehicles')
                     ->latest()
                     ->get();
            $data = $zones->map(function ($zone) {
                $midpoints = [];
                if (is_array($zone->mid_name) && is_array($zone->mid_latitude) && is_array($zone->mid_longitude) && is_array($zone->pickup_time) && is_array($zone->return_time)) {
                    foreach ($zone->mid_name as $index => $midName) {
                        $midpoints[] = [
                            'mid_name' => $midName,
                            'mid_latitude' => $zone->mid_latitude[$index] ?? null,
                            'mid_longitude' => $zone->mid_longitude[$index] ?? null,
                        ];
                        $pickupTime[]= [
                            'pickup_time' => $zone->pickup_time[$index] ?? null,
                        ];
                        $returnTime[] = [
                            'return_time' => $zone->return_time[$index] ?? null,
                        ];
                    }
                }
        return [
            'id' => $zone->id,
            'name' => $zone->name,
            'vehicle_id' => $zone->vehicle_id,
            'midpoints' => $midpoints,
            'schools_id' => $zone->schools_id,
            'created_at' => $zone->created_at,
            'updated_at' => $zone->updated_at,
            'vehicles' => $zone->vehicles,
            'schools' => $zone->schools,
            'return_time' => $returnTime,
            'pickup_time' => $pickupTime,
        ];
    });

    return response()->json([
        'success' => true,
        'message' => 'All Data successfully retrieved',
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
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create zone.',
                'errors' => $validator->errors(),
            ], 400);
        }
        $requestData = $request->only(['name', 'vehicle_id', 'schools_id', 'mid_name','mid_latitude', 'mid_longitude', 'pickup_time', 'return_time']);
        $data = new Zone();
        $data->fill($requestData);
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Zone created successfully',
            'data' => $data,
        ], 201);
    }
    /**
     * Display the specified resource.
     */

    // public function show(string $id)
    // {
    //     $data = Zone::with('pickup_points:id,title,pickup_name,drop_name')->find($id);

    //     if (!$data) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Zone not found',
    //         ], 404);
    //     }

    //     $data->vehicles = $data->vehicles;

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Zone retrieved successfully',
    //         'data' => $data,
    //     ]);
    // }
    public function show(string $id)
    {
        $zone = Zone::with(['pickup_points:id,title,pickup_name,drop_name', 'vehicles'])->find($id);

        if (!$zone) {
            return response()->json([
                'success' => false,
                'message' => 'Zone not found',
            ], 404);
        }

            $midpoints = [];
            if (is_array($zone->mid_name) && is_array($zone->mid_latitude) && is_array($zone->mid_longitude) && is_array($zone->pickup_time) && is_array($zone->return_time)) {
                foreach ($zone->mid_name as $index => $midName) {
                    $midpoints[] = [
                        'mid_name' => $midName,
                        'mid_latitude' => $zone->mid_latitude[$index] ?? null,
                        'mid_longitude' => $zone->mid_longitude[$index] ?? null,
                    ];
                    $pickupTime[]= [
                        'pickup_time' => $zone->pickup_time[$index] ?? null,
                    ];
                    $returnTime[] = [
                        'return_time' => $zone->return_time[$index] ?? null,
                    ];
                }
            }
    $data = [
        'id' => $zone->id,
        'name' => $zone->name,
        'vehicle_id' => $zone->vehicle_id,
        'midpoints' => $midpoints,
        'schools_id' => $zone->schools_id,
        'created_at' => $zone->created_at,
        'updated_at' => $zone->updated_at,
        'vehicles' => $zone->vehicles,
        'schools' => $zone->schools,
        'return_time' => $returnTime,
        'pickup_time' => $pickupTime,
    ];
        return response()->json([
            'success' => true,
            'message' => 'Zone retrieved successfully',
            'data' => $data,
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = Zone::find($id);

        if ($data) {
            if (!empty($request->input('name'))) {
                $data->name = $request->input('name');
            }
            if (!empty($request->input('mid_name'))) {
                $midName = $request->input('mid_name');
                if (is_array($midName)) {
                    $data->mid_name = $midName;
                }
            }
            if (!empty($request->input('schools_id'))) {
                $data->schools_id = $request->input('schools_id');
            }
            if (!empty($request->input('vehicle_id'))) {
                $vehicleID = $request->input('vehicle_id');
                if (is_array($vehicleID)) {
                    $data->vehicle_id = $vehicleID;
                }
            }
            if (!empty($request->input('mid_latitude'))) {
                $midLatitude = $request->input('mid_latitude');
                if (is_array($midLatitude)) {
                    $data->mid_latitude = $midLatitude;
                }
            }
            if (!empty($request->input('mid_longitude'))) {
                $midLongitude = $request->input('mid_longitude');
                if (is_array($midLongitude)) {
                    $data->mid_longitude = $midLongitude;
                }
            }
            if (!empty($request->input('pickup_time'))) {
                $pickupTimes = $request->input('pickup_time');
                if (is_array($pickupTimes)) {
                    $data->pickup_time = $pickupTimes;
                }
            }
            if (!empty($request->input('return_time'))) {
                $returnTimes = $request->input('return_time');
                if (is_array($returnTimes)) {
                    $data->return_time = $returnTimes;
                }
            }
            // if (!empty($request->input('mid_name'))) {
            //     $data->mid_name = $request->input('mid_name');
            // }
            // if (!empty($request->input('vehicle_id'))) {
            //     $data->vehicle_id = $request->input('vehicle_id');
            // }
            // if (!empty($request->input('mid_latitude'))) {
            //     $data->mid_latitude = $request->input('mid_latitude');
            // }
            // if (!empty($request->input('mid_longitude'))) {
            //     $data->mid_longitude = $request->input('mid_longitude');
            // }
            // if (!empty($request->input('pickup_time'))) {
            //     $data->pickup_time  = $request->input('pickup_time');
            // }
            // if (!empty($request->input('return_time'))) {
            //     $data->return_time = $request->input('return_time');
            // }
            $data->save();

            return response()->json([
                'success' => true,
                'message' => 'Zone is updated successfully',
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Zone not found.',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Zone::find($id);
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
