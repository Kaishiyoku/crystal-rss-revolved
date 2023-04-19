<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedDiscovererRequest;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Arr;
use Kaishiyoku\HeraRssCrawler\HeraRssCrawler;

class FeedDiscovererController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function __invoke(FeedDiscovererRequest $request, HeraRssCrawler $heraRssCrawler)
    {
        $validated = $request->validated();

        try {
            $discoveredFeedUrls = $heraRssCrawler->discoverFeedUrls(Arr::get($validated, 'feed_url'));

            if ($discoveredFeedUrls->isEmpty()) {
                abort(404, 'No feeds found.');
            }

            $feedMetadata = $heraRssCrawler->parseFeed($discoveredFeedUrls->first());

            return response()->json([
                'feed_url' => $feedMetadata->getFeedUrl() ?? $feedMetadata->getUrl(),
                'site_url' => $feedMetadata->getUrl(),
                'favicon_url' => $heraRssCrawler->discoverFavicon($feedMetadata->getUrl()) ?? '',
                'name' => $feedMetadata->getTitle(),
                'language' => $feedMetadata->getLanguage() ?? '',
            ]);
        } catch (ConnectException $e) {
            abort(422, 'The given URL is invalid.');
        } catch (ClientException $e) {
            abort(422, 'The given URL could not be resolved.');
        }
    }
}
