<?php

namespace App\Http\Controllers\api;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{

    public function index()
    {
        $data = Vehicle::latest()->get();
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

    public function vehicleIndex($type)
    {
        $data = Vehicle::where('vehicle_type', $type)->latest()->get();
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



    public function show($id)
    {
        $data = Vehicle::find($id);

        // foreach ($data as $Driver) {
        //     $Driver->image = json_decode($Driver->image); // Decode the JSON-encoded location string
        // }

        return response()->json([
            'success' => true,
            'message' => 'All Data successful',
            'data' => $data,
        ]);
    }

    public function notAssign()
    {
        $data = Vehicle::with('driver:id,vehicle_id,name,email', )
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
            $vehicles = Vehicle::create($request->post());
            if ($files = $request->file('image')) { // Assuming 'vehicle_images' is the input name for multiple files
                $imageUrls = []; // Initialize an array to store the image URLs
                foreach ($files as $file) {
                    $image_name = md5(rand(1000, 10000)) . '.' . $file->getClientOriginalExtension();
                    $upload_path = 'vehicleImage/';
                    $image_url = $upload_path . $image_name;
                    $file->move($upload_path, $image_name);
                    $imageUrls[] = $image_url; // Store the image URL in the array
                }
                $vehicles->image = $imageUrls; // Store the array of image URLs in the driver object
            }
            $vehicles->save();
            return response()->json([
                'success' => true,
                'message' => 'vehicle Create successfull',
                'date' => $vehicles,
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
        $obj = Vehicle::find($id);
        if ($obj) {
            if (!empty($request->input('name'))) {
                $obj->name = $request->input('name');
            }
            if (!empty($request->input('category'))) {
                $obj->category = $request->input('category');
            }
            if (!empty($request->input('reg_no'))) {
                $obj->reg_no = $request->input('reg_no');
            }
            if (!empty($request->input('seating_capacity'))) {
                $obj->seating_capacity = $request->input('seating_capacity');
            }
            if (!empty($request->input('pickup_location'))) {
                $obj->pickup_location = $request->input('pickup_location');
            }
            if (!empty($request->input('drop_location'))) {
                $obj->drop_location = $request->input('drop_location');
            }
            if (!empty($request->input('per_km'))) {
                $obj->per_km = $request->input('per_km');
            }
            if (!empty($request->input('image'))) {
                $obj->image = $request->input('image');
            }
            if (!empty($request->input('vehicle_type'))) {
                $obj->vehicle_type = $request->input('vehicle_type');
            }
            if (!empty($request->input('vehicle_number'))) {
                $obj->vehicle_number = $request->input('vehicle_number');
            }
            if (!empty($request->input('color'))) {
                $obj->color = $request->input('color');
            }
            // if ($files = $request->file('image')) { // Assuming 'vehicle_images' is the input name for multiple files
            //     $imageUrls = []; // Initialize an array to store the image URLs
            //     foreach ($files as $file) {
            //         $image_name = md5(rand(1000, 10000)) . '.' . $file->getClientOriginalExtension();
            //         $upload_path = 'vehicleImage/';
            //         $image_url = $upload_path . $image_name;
            //         $file->move($upload_path, $image_name);
            //         $imageUrls[] = $image_url; // Store the image URL in the array
            //     }
            //     $obj->image = $imageUrls; // Update the array of image URLs in the object
            // }
            $obj->save();
        }
        return response()->json([
            'success' => true,
            'message' => 'Vehicle is updated successfully',
            'data' => $obj,
        ]);
    }

    public function destroy($id)
    {
        $program = Vehicle::find($id);
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
}
