<?php

namespace App\Http\Livewire;

use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Facades\Validator;
use Kaishiyoku\HeraRssCrawler\HeraRssCrawler;
use Livewire\Component;

class FeedDiscoverer extends Component
{
    /**
     * @var \App\Models\Feed
     */
    public $feed;

    /**
     * @var string
     */
    public $siteUrlInputElementSelector;

    /**
     * @var string
     */
    public $nameInputElementSelector;

    /**
     * @var \Illuminate\Support\Collection
     */
    public $discoveredFeedUrls;

    /**
     * @var \Illuminate\Support\MessageBag
     */
    public $validationErrors;

    /**
     * @var \Kaishiyoku\HeraRssCrawler\HeraRssCrawler
     */
    private $heraRssCrawler;

    public function __construct()
    {
        parent::__construct();

        $this->heraRssCrawler = new HeraRssCrawler();
    }

    public function mount()
    {
        $this->discoveredFeedUrls = collect();
        $this->validationErrors = collect();
    }

    public function render()
    {
        return view('livewire.feed-discoverer');
    }

    public function discover($url)
    {
        $validator = Validator::make(['url' => $url], [
            'url' => ['required', 'url'],
        ]);

        if ($validator->fails()) {
            $this->emit('discoveryFailed', $validator->errors()->first('url'));

            return;
        }

        try {
            $this->discoveredFeedUrls = $this->heraRssCrawler->discoverFeedUrls($url);

            $this->emit('discoverySuccess');
        } catch (ConnectException $e) {
            $this->emit('discoveryFailed', __('The given URL is invalid.'));
        }
    }

    public function retrieveFeedMetadata($feedUrl)
    {
        $feed = $this->heraRssCrawler->parseFeed($feedUrl);

        $this->emit('feedMetadata', [
            'siteUrl' => $feed->getUrl(),
            'name' => $feed->getTitle(),
        ]);
    }
}
