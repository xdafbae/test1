<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Show all favorites for the authenticated user.
     */
    public function index()
    {
        $favorites = Auth::user()->favorites()->latest()->get();
        return view('favorites.index', compact('favorites'));
    }

    /**
     * Add a movie to favorites.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'imdb_id' => 'required|string',
            'title'   => 'required|string',
            'year'    => 'nullable|string',
            'poster'  => 'nullable|string',
            'type'    => 'nullable|string',
        ]);

        Auth::user()->favorites()->firstOrCreate(
            ['imdb_id' => $data['imdb_id']],
            $data
        );

        if ($request->expectsJson()) {
            return response()->json(['status' => 'added', 'message' => __('favorites.added')]);
        }

        return back()->with('success', __('favorites.added'));
    }

    /**
     * Remove a movie from favorites.
     */
    public function destroy(string $imdbId)
    {
        Auth::user()->favorites()->where('imdb_id', $imdbId)->delete();

        if (request()->expectsJson()) {
            return response()->json(['status' => 'removed', 'message' => __('favorites.removed')]);
        }

        return back()->with('success', __('favorites.removed'));
    }
}
