<?php

namespace App\Http\Controllers\Api;

use App\Models\Challenge;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;


class ChallengeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $challenges = Challenge::paginate(10);
        return response()->json($challenges);
    }

    public function store(Request $request)
    {

        try {

            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'difficulty' => 'required|numeric',
            ]);

            $validatedData['user_id'] = Auth::id();

            $challenge = Challenge::create($validatedData);

            return response()->json($challenge, 201);
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
            $challenge = Challenge::findOrFail($id);

            return response()->json($challenge);
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
            $challenge = Challenge::findOrFail($id);

            $validatedData = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'required|string',
                'difficulty' => 'required|numeric',
            ]);

            $challenge->update($validatedData);

            return response()->json($challenge);
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
            $challenge = Challenge::findOrFail($id);
            $challenge->delete();

            return response()->json([
                'success' => true,
                'message' => "Delete success",
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
