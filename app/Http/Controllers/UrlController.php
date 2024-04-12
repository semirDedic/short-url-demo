<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUrlRequest;
use App\Models\Url;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class UrlController extends Controller
{
    public function __construct(
        protected Url $urls,
    ) {
    }

    public function index()
    {
        return Inertia::render('Short');
    }

    public function generateHash(StoreUrlRequest $request): RedirectResponse
    {

        try {
            $validated = $request->validated();

            $originalUrl = $validated['url'];

            // check if url is safe
            if (!$this->isUrlSafe($originalUrl)) {
                return to_route('welcome')
                    ->withErrors([
                        'url' => 'The link is not safe'
                    ]);
            }

            // create hash and strip string to 6 characters
            $hash = $this->urls->generateUniqueHash($originalUrl);

            $url = $this->urls->firstOrNew(
                ['original_url' => $originalUrl],
                [
                    'hashed_url' => $hash,
                    'expires_at' => NULL, // TODO: make url expire after set datetime
                ],
            );

            $url->save();


            if ($url->id) {
                return to_route('welcome')
                    ->with([
                        'message' => 'Successfully generated short url',
                        'shortUrl' => url($hash)
                    ]);
            }

            return to_route('welcome')
                ->with([
                    'message' => 'Something goes wrong. Please try after sometime.',
                ]);
        } catch (ValidationException $th) {
            return to_route('welcome')
                ->with([
                    'message' => $th->getMessage(),
                ]);
        }
    }

    public function redirectToRealUrl($hash): RedirectResponse
    {
        // Get the Original URL on the basis of Hash.
        $url = $this->urls->where('hashed_url', $hash)->first();

        if (!$url) {
            return to_route('welcome')
                ->with([
                    'message' => 'URL not found',
                ]);
        }

        // Increase click counter By 1
        $url->update([
            'clicks' => $url->clicks + 1
        ]);

        // Redirect to original URL
        return Redirect::to($url->original_url);
    }


    public function isUrlSafe(string $url): bool
    {

        $response = Http::post(
            config('services.google.url') . config('services.google.url_prefix') . config('services.google.key'),
            [
                'client' => [
                    'clientId' => 'testingcompany',
                    'clientVersion' => '1.5.2',
                ],
                'threatInfo' => [
                    'threatTypes' => ['MALWARE', 'SOCIAL_ENGINEERING'],
                    'platformTypes' => ['WINDOWS'],
                    'threatEntryTypes' => ['URL'],
                    'threatEntries' => [
                        'url' => $url,
                    ]
                ],
            ]
        );

        return empty($response->json());
    }
}
