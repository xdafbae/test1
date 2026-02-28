<?php

namespace App\Http\Controllers;

use App\Services\OmdbService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovieController extends Controller
{
    public function __construct(protected OmdbService $omdb) {}

    /**
     * Show initial movie listing page.
     */
    public function index(Request $request)
    {
        $query = trim((string) $request->input('q', ''));
        $type  = trim((string) $request->input('type', ''));
        $year  = trim((string) $request->input('year', ''));

        $movies      = [];
        $total       = 0;
        $hasMore     = false;
        $error       = null;
        $favoriteIds = Auth::user()->favorites()->pluck('imdb_id')->toArray();

        // Only hit the API when there is an actual search query
        if ($query !== '') {
            $data    = $this->omdb->search($query, 1, $type, $year);
            $movies  = $data['Search'] ?? [];
            $total   = (int) ($data['totalResults'] ?? 0);
            $hasMore = $total > 10;
            $error   = ($data['Response'] === 'False') ? ($data['Error'] ?? '') : null;
        }

        return view('movies.index', compact(
            'movies', 'total', 'hasMore', 'query', 'type', 'year', 'favoriteIds', 'error'
        ));
    }

    /**
     * JSON endpoint for infinite scroll / AJAX search.
     */
    public function search(Request $request)
    {
        $query = trim((string) $request->input('q', ''));
        $page  = max(1, (int) $request->input('page', 1));
        $type  = trim((string) $request->input('type', ''));
        $year  = trim((string) $request->input('year', ''));

        if ($query === '') {
            return response()->json(['movies' => [], 'hasMore' => false, 'total' => 0]);
        }

        $data    = $this->omdb->search($query, $page, $type, $year);
        $movies  = $data['Search'] ?? [];
        $total   = (int) ($data['totalResults'] ?? 0);
        $hasMore = ($page * 10) < $total;

        // Mark favorites
        $favoriteIds = Auth::user()->favorites()->pluck('imdb_id')->toArray();
        foreach ($movies as &$movie) {
            $movie['isFavorite'] = in_array($movie['imdbID'], $favoriteIds);
        }

        return response()->json([
            'movies'  => $movies,
            'hasMore' => $hasMore,
            'total'   => $total,
            'page'    => $page,
        ]);
    }

    /**
     * Show movie detail page.
     */
    public function show(string $imdbId)
    {
        $movie = $this->omdb->getDetail($imdbId);

        if (($movie['Response'] ?? 'False') === 'False') {
            abort(404, $movie['Error'] ?? 'Movie not found');
        }

        $isFavorite = Auth::user()
            ->favorites()
            ->where('imdb_id', $imdbId)
            ->exists();

        return view('movies.show', compact('movie', 'isFavorite'));
    }
}
