<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OmdbService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://www.omdbapi.com/';

    public function __construct()
    {
        $this->apiKey = config('omdb.api_key');
    }

    /**
     * Search movies by title and optional filters.
     */
    public function search(string $query, int $page = 1, string $type = '', string $year = ''): array
    {
        $params = [
            'apikey' => $this->apiKey,
            's'      => $query,
            'page'   => $page,
        ];

        if ($type) {
            $params['type'] = $type;
        }
        if ($year) {
            $params['y'] = $year;
        }

        $response = Http::timeout(10)->get($this->baseUrl, $params);

        if ($response->failed()) {
            return ['Search' => [], 'totalResults' => 0, 'Response' => 'False'];
        }

        return $response->json();
    }

    /**
     * Get full details of a movie by IMDb ID.
     */
    public function getDetail(string $imdbId): array
    {
        $response = Http::timeout(10)->get($this->baseUrl, [
            'apikey' => $this->apiKey,
            'i'      => $imdbId,
            'plot'   => 'full',
        ]);

        if ($response->failed()) {
            return ['Response' => 'False', 'Error' => 'Request failed'];
        }

        return $response->json();
    }
}
