<?php

namespace App\Http\Controllers;

use App\Models\FeedItem;
use fivefilters\Readability\Configuration;
use fivefilters\Readability\ParseException;
use fivefilters\Readability\Readability;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Enums\Format;
use Spatie\LaravelPdf\Enums\Unit;
use Spatie\LaravelPdf\Facades\Pdf;

class FeedItemPdfController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, FeedItem $feedItem): Response
    {
        $this->authorize('pdf', $feedItem);

        $readability = new Readability((new Configuration)
            ->setFixRelativeURLs(true)
            ->setOriginalURL($feedItem->url)
        );

        try {
            $readability->parse(Http::get($feedItem->url));

            return Pdf::view('readability.article', [
                'lang' => $feedItem->feed->language,
                'url' => $feedItem->url,
                'title' => $readability->getTitle(),
                'author' => $readability->getAuthor(),
                'content' => $readability->getContent(),
            ])
                ->withBrowsershot(function (Browsershot $browsershot) {
                    $browsershot->delay(300);
                    $browsershot->dismissDialogs();
                })
                ->format(Format::A4)
                ->margins(25, 20, 25, 20, Unit::Millimeter)
                ->toResponse($request);

        } catch (ParseException) {
            abort(404);
        }
    }
}
