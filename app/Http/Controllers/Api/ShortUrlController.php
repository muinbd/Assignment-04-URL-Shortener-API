<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShortUrl;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShortUrlController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $urls = $request->user()
            ->shortUrls()
            ->latest()
            ->paginate(10);

        return response()->json($urls, 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'original_url' => ['required', 'url:http,https', 'max:2048'],
            'expires_at' => ['nullable', 'date', 'after:now'],
        ]);

        $shortCode = $this->generateUniqueShortCode();

        $shortUrl = $request->user()->shortUrls()->create([
            'original_url' => $validated['original_url'],
            'short_code' => $shortCode,
            'expires_at' => $validated['expires_at'] ?? null,
        ]);

        return response()->json([
            'message' => 'Short URL created successfully',
            'data' => $shortUrl,
        ], 201);
    }

    public function show(ShortUrl $url): JsonResponse
    {
        $this->authorize('view', $url);

        return response()->json($url, 200);
    }

    public function update(Request $request, ShortUrl $url): JsonResponse
    {
        $this->authorize('update', $url);

        $validated = $request->validate([
            'original_url' => ['sometimes', 'required', 'url:http,https', 'max:2048'],
            'expires_at' => ['sometimes', 'nullable', 'date', 'after:now'],
        ]);

        if ($validated === []) {
            return response()->json([
                'message' => 'At least one field (original_url or expires_at) is required.',
            ], 422);
        }

        $url->update($validated);

        return response()->json([
            'message' => 'Short URL updated successfully',
            'data' => $url->fresh(),
        ], 200);
    }

    public function destroy(ShortUrl $url)
    {
        $this->authorize('delete', $url);

        $url->delete();

        return response()->noContent();
    }

    private function generateUniqueShortCode(int $length = 6): string
    {
        do {
            $code = Str::random($length);
        } while (ShortUrl::where('short_code', $code)->exists());

        return $code;
    }
}