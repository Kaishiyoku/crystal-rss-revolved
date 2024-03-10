<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FeedUrlDiscovererRequest;
use Illuminate\Support\Arr;
use Kaishiyoku\HeraRssCrawler\HeraRssCrawler;

class FeedUrlDiscovererController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function __invoke(FeedUrlDiscovererRequest $request, HeraRssCrawler $heraRssCrawler)
    {
        $validated = $request->validated();

        return response()->json($heraRssCrawler->discoverFeedUrls(Arr::get($validated, 'feed_url')));
    }
}
