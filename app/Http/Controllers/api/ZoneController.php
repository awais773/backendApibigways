<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use App\Models\ZoneTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $zones = Zone::with('schools', 'zonetimes')->latest()->get();

        $data = $zones->map(function ($zone) {
            $midpoints = [];

            if (is_array($zone->mid_name) && is_array($zone->mid_latitude) && is_array($zone->mid_longitude)) {
                foreach ($zone->mid_name as $index => $midName) {
                    $midpoints[] = [
                        'mid_name' => $midName,
                        'mid_latitude' => $zone->mid_latitude[$index] ?? null,
                        'mid_longitude' => $zone->mid_longitude[$index] ?? null,
                    ];
                }
            }

            $zoneTimes = $zone->zonetimes->map(function ($zoneTime) {
                return [
                    'vehicle_id' => $zoneTime->vehicle_id,
                    'vehicles' => $zoneTime->vehicles,
                    'pickup_time' => $zoneTime->pickup_time,
                    'return_time' => $zoneTime->return_time,
                ];
            });

            return [
                'id' => $zone->id,
                'name' => $zone->name,
                'midpoints' => $midpoints,
                'schools_id' => $zone->schools_id,
                'created_at' => $zone->created_at,
                'updated_at' => $zone->updated_at,
                'schools' => $zone->schools,
                'zone_pickup_name' => $zone->zone_pickup_name,
                'zone_pickup_latitude' => $zone->zone_pickup_latitude,
                'zone_pickup_longitude' => $zone->zone_pickup_longitude,
                'zone_times' => $zoneTimes,
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

        $zone = Zone::create($request->only(['name', 'schools_id', 'mid_name','mid_latitude', 'mid_longitude','zone_pickup_name','zone_pickup_latitude','zone_pickup_longitude']));

            $zoneTime = ZoneTime::create([
                'zone_id' => $zone->id,
                'vehicle_id' => $request->vehicle_id,
                'pickup_time' => $request->pickup_time ,
                'return_time' => $request->return_time,
            ]);
            $data = ['zone' => $zone , 'zoneTime' => $zoneTime];
        return response()->json([
            'success' => true,
            'message' => 'Zone created successfully',
            'data' => $data,
        ], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $zone = Zone::with('schools', 'zonetimes')->find($id);

        if (!$zone) {
            return response()->json([
                'success' => false,
                'message' => 'Zone not found',
            ], 404);
        }

        $midpoints = [];

        if (is_array($zone->mid_name) && is_array($zone->mid_latitude) && is_array($zone->mid_longitude)) {
            foreach ($zone->mid_name as $index => $midName) {
                $midpoints[] = [
                    'mid_name' => $midName,
                    'mid_latitude' => $zone->mid_latitude[$index] ?? null,
                    'mid_longitude' => $zone->mid_longitude[$index] ?? null,
                ];
            }
        }
        $zoneTimes = $zone->zonetimes->map(function ($zoneTime) {
            return [
                'vehicle_id' => $zoneTime->vehicle_id,
                'vehicles' => $zoneTime->vehicles,
                'pickup_time' => $zoneTime->pickup_time,
                'return_time' => $zoneTime->return_time,
            ];
        });
        $data = [
            'id' => $zone->id,
            'name' => $zone->name,
            'midpoints' => $midpoints,
            'schools_id' => $zone->schools_id,
            'created_at' => $zone->created_at,
            'updated_at' => $zone->updated_at,
            'schools' => $zone->schools,
            'zone_pickup_name' => $zone->zone_pickup_name,
            'zone_pickup_latitude' => $zone->zone_pickup_latitude,
            'zone_pickup_longitude' => $zone->zone_pickup_longitude,
            'zone_times' => $zoneTimes,
        ];
        return response()->json([
            'success' => true,
            'message' => 'Data successfully retrieved',
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
            if (!empty($request->input('zone_pickup_name'))) {
                $data->zone_pickup_name = $request->input('zone_pickup_name');
            }
            if (!empty($request->input('zone_pickup_latitude'))) {
                $data->zone_pickup_latitude = $request->input('zone_pickup_latitude');
            }
            if (!empty($request->input('zone_pickup_longitude'))) {
                $data->zone_pickup_longitude = $request->input('zone_pickup_longitude');
            }
            if (!empty($request->input('vehicle_id'))) {
                $ZoneTime = ZoneTime::where('zone_id',$data->id)->first();
                $ZoneTime->vehicle_id = $request->input('vehicle_id');
             }
             if (!empty($request->input('pickup_time'))) {
                $ZoneTime = ZoneTime::where('zone_id',$data->id)->first();
                $ZoneTime->pickup_time= $request->input('pickup_time');
             }
             if (!empty($request->input('return_time'))) {
                $ZoneTime = ZoneTime::where('zone_id',$data->id)->first();
                $ZoneTime->return_time = $request->input('return_time');
             }
            $ZoneTime->save();
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
    public function addvehicle(Request $request)
{
    if (!empty($request->input('vehicle_id'))) {
        $data = ZoneTime::create([
            'zone_id' => $request->zone_id,
            'vehicle_id' => $request->vehicle_id,
            'pickup_time' => $request->pickup_time ,
            'return_time' => $request->return_time,
        ]);
    }
 return response()->json([
    'success' => true,
    'message' => 'Vehicle Added.',
    'data' => $data,
 ]);
}
}
