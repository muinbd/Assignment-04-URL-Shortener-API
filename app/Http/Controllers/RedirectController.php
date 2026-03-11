<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Response;

class RedirectController extends Controller
{
    public function handle(string $short_code)
    {
        $shortUrl = ShortUrl::where('short_code', $short_code)->first();

        if (! $shortUrl) {
            abort(404, 'Short URL not found.');
        }

        if ($shortUrl->expires_at && now()->greaterThan($shortUrl->expires_at)) {
            abort(410, 'Short URL has expired.');
        }

        $shortUrl->increment('clicks');

        return redirect()->away($shortUrl->original_url, Response::HTTP_FOUND); // 302
    }
}
