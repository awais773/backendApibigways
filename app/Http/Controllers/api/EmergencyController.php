<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Emergency;
use Illuminate\Support\Facades\Validator;

class EmergencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Emergency::latest()->get();
        if (is_null($data)) {
            return response()->json('data not found',);
        }
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
            // 'image' => 'required',
            'message' => 'required|string',
         ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $Emergency = Emergency::create($request->post());
        if ($file = $request->file('image')) {
            $video_name = md5(rand(1000, 10000));
            $ext = strtolower($file->getClientOriginalExtension());
            $video_full_name = $video_name . '.' . $ext;
            $upload_path = 'emergencyPicture/';
            $video_url = $upload_path . $video_full_name;
            $file->move($upload_path, $video_url);
            $Emergency->image = $video_url;
        }
        $Emergency->save();

        return response()->json(['message' => 'Emergency reported successfully'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = Emergency::find($id);

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
        $data = Emergency::find($id);

        if ($data) {
            if (!empty($request->input('message'))) {
                $data->message = $request->input('message');
            }
            if ($file = $request->file('image')) {
                $video_name = md5(rand(1000, 10000));
                $ext = strtolower($file->getClientOriginalExtension());
                $video_full_name = $video_name . '.' . $ext;
                $upload_path = 'emergencyPicture/';
                $video_url = $upload_path . $video_full_name;
                $file->move($upload_path, $video_url);
                $data->image = $video_url;
            }
            $data->save();
            return response()->json([
                'success' => true,
                'message' => 'Emergency is updated successfully',
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Emergency not found.',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Emergency::find($id);
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
