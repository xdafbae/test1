@php
    $poster = $movie['Poster'] ?? '';
    $poster = ($poster && $poster !== 'N/A') ? $poster : '';
    $imdbId = $movie['imdbID'] ?? '';
    $title  = $movie['Title'] ?? '';
    $year   = $movie['Year'] ?? '—';
    $type   = $movie['Type'] ?? 'movie';
    $isFav  = in_array($imdbId, $favoriteIds ?? []);
@endphp

<div class="movie-card">
    <a href="/movies/{{ $imdbId }}">
        <div class="poster-wrap">
            @if($poster)
                <img
                    class="poster-img lazy"
                    data-src="{{ $poster }}"
                    src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                    alt="{{ $title }}"
                    loading="lazy"
                >
            @else
                <div class="poster-placeholder">
                    <span>🎬</span>
                    <span>No Image</span>
                </div>
            @endif

            <div class="poster-overlay">
                <div class="overlay-actions">
                    <button
                        class="fav-btn-card {{ $isFav ? 'is-fav' : '' }}"
                        data-id="{{ $imdbId }}"
                        data-fav="{{ $isFav ? 1 : 0 }}"
                        data-title="{{ addslashes($title) }}"
                        data-year="{{ $year }}"
                        data-poster="{{ $poster }}"
                        data-type="{{ $type }}"
                    >{{ $isFav ? '♥ ' . __('app.remove_favorite') : '♡ ' . __('app.add_favorite') }}</button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="card-type">{{ $type }}</div>
            <div class="card-title-text">{{ $title }}</div>
            <div class="card-year">{{ $year }}</div>
        </div>
    </a>
</div>
