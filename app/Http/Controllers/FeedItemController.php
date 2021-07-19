<?php

namespace App\Http\Controllers;

use App\Models\FeedItem;
use Illuminate\Http\Request;

class FeedItemController extends Controller
{
    public function toggleMarkAsRead(FeedItem $feedItem)
    {
        $feedItem->read_at = $feedItem->read_at ? null : now();

        $feedItem->save();

        return response()->json($feedItem);
    }
}
