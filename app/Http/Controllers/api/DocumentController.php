<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;


class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Document::with('student', 'driver')->latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'All Data successfully',
            'data' => $data,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function documentStore(Request $request)
    {
        $request->validate([
            // 'documents' => 'required|file|max:10240' // Limit size to 10MB
        ]);

        $data = Document::create($request->post());
        if ($file = $request->file('documents')) {
            $video_name = md5(rand(1000, 10000));
            $ext = strtolower($file->getClientOriginalExtension());
            $video_full_name = $video_name . '.' . $ext;
            $upload_path = 'Documents/';
            $video_url = $upload_path . $video_full_name;
            $file->move($upload_path, $video_url);
            $data->documents = $video_url;
        }
        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'Expense Create successfully',
            'date' => $data,
        ], 200);
        }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Document::with('student')->find($id);
        return response()->json([
            'success' => true,
            'message' => 'All Data successful',
            'data' => $data,
        ]);

    }
}
