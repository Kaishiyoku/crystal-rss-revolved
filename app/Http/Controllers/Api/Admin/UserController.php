<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'users' => User::orderBy('name')
                ->withCount([
                    'feeds',
                    'feedItems as unread_feed_items_count' => function (Builder $query) {
                        $query->whereNull('read_at');
                    },
                ])
                ->get(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        $user->feedItems()->delete();
        $user->feeds()->delete();
        $user->categories()->delete();
        $user->delete();

        return response()->json();
    }
}
