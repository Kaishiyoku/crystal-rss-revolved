<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Admin/Users/Index', [
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
    public function destroy(User $user): RedirectResponse
    {
        $user->feedItems()->delete();
        $user->feeds()->delete();
        $user->categories()->delete();
        $user->delete();

        return redirect()->route('admin.users.index');
    }
}
