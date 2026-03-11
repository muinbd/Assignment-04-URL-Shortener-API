<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        return response()->json($request->user(), 200);
    }

    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user->fresh(),
        ], 200);
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        // delete current token first (optional but clean)
        $user->currentAccessToken()?->delete();

        // short_urls will be deleted automatically if FK cascadeOnDelete is set
        $user->delete();

        return response()->noContent(); // 204
    }
}
