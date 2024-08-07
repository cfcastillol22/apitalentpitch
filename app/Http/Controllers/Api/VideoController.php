<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $videos = Video::paginate(10);
        return response()->json($videos);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'description' => 'required|string',
                'url' => 'required|url',
                'logo' => 'required|url',
            ]);

            $validatedData['user_id'] = Auth::id();

            $video = Video::create($validatedData);

            return response()->json($video, 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function show($id)
    {
        try {
            $video = Video::findOrFail($id);

            return response()->json($video);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $video = Video::findOrFail($id);

            $validatedData = $request->validate([
                'description' => 'sometimes|required|string',
                'url' => 'sometimes|required|url',
                'logo' => 'sometimes|required|url',
            ]);

            $video->update($validatedData);

            return response()->json($video);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $video = Video::findOrFail($id);
            $video->delete();

            return response()->json(null, 204);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
