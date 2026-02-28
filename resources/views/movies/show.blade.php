@extends('layouts.app')

@section('title', ($movie['Title'] ?? 'Movie Detail'))
@section('meta_description', Str::limit($movie['Plot'] ?? '', 160))

@push('styles')
<style>
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        color: var(--muted);
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 1.75rem;
        transition: color 0.2s;
    }
    .back-link:hover { color: var(--text); }

    .detail-wrapper {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 2.5rem;
        align-items: start;
    }

    /* Poster */
    .detail-poster {
        position: sticky;
        top: 80px;
    }
    .detail-poster img {
        width: 100%;
        border-radius: var(--radius-lg);
        box-shadow: 0 20px 60px rgba(0,0,0,0.6);
        display: block;
    }
    .poster-no-img {
        width: 100%;
        aspect-ratio: 2/3;
        background: linear-gradient(135deg, var(--card2), #0e0e1a);
        border-radius: var(--radius-lg);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        color: var(--muted);
        gap: 0.5rem;
        box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    }
    .poster-no-img span { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.06em; }

    /* Main info */
    .detail-info {}

    .detail-type {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background: rgba(124,106,247,0.15);
        border: 1px solid rgba(124,106,247,0.3);
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--accent2);
        margin-bottom: 0.75rem;
    }

    .detail-title {
        font-size: clamp(1.5rem, 3vw, 2.5rem);
        font-weight: 800;
        letter-spacing: -0.5px;
        line-height: 1.15;
        margin-bottom: 0.5rem;
    }

    .detail-tagline {
        color: var(--muted);
        font-size: 0.9rem;
        margin-bottom: 1.25rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        align-items: center;
    }
    .detail-tagline span {
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }
    .detail-tagline .dot { color: var(--border); }

    /* Rating */
    .ratings-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1.75rem;
    }
    .rating-chip {
        background: var(--card2);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 0.6rem 0.875rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        min-width: 90px;
    }
    .rating-source { font-size: 0.65rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.2rem; }
    .rating-value { font-size: 1rem; font-weight: 800; color: var(--gold); }

    /* Favorite button */
    .fav-action {
        margin-bottom: 2rem;
    }

    /* Meta grid */
    .meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    .meta-item {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 0.875rem 1rem;
    }
    .meta-label {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: var(--muted);
        margin-bottom: 0.3rem;
    }
    .meta-value {
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--text);
        line-height: 1.4;
    }

    /* Plot */
    .section-title {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--muted);
        margin-bottom: 0.75rem;
    }
    .plot-text {
        font-size: 0.95rem;
        line-height: 1.75;
        color: #cbd5e1;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 1.25rem;
    }

    @media (max-width: 900px) {
        .detail-wrapper { grid-template-columns: 1fr; }
        .detail-poster { position: static; max-width: 260px; }
    }
    @media (max-width: 640px) {
        .meta-grid { grid-template-columns: 1fr 1fr; }
    }
</style>
@endpush

@section('content')
<a href="{{ route('movies.index') }}" class="back-link">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
    {{ __('app.detail_back') }}
</a>

<div class="detail-wrapper">
    {{-- Poster --}}
    <div class="detail-poster">
        @php $poster = ($movie['Poster'] ?? '') !== 'N/A' ? ($movie['Poster'] ?? '') : ''; @endphp
        @if($poster)
            <img src="{{ $poster }}" alt="{{ $movie['Title'] ?? '' }}" loading="lazy">
        @else
            <div class="poster-no-img">🎬<span>No Image</span></div>
        @endif
    </div>

    {{-- Info --}}
    <div class="detail-info">
        <div class="detail-type">{{ $movie['Type'] ?? 'movie' }}</div>
        <h1 class="detail-title">{{ $movie['Title'] ?? 'Unknown Title' }}</h1>

        <div class="detail-tagline">
            @if(!empty($movie['Year']))
                <span>📅 {{ $movie['Year'] }}</span>
                <span class="dot">•</span>
            @endif
            @if(!empty($movie['Runtime']) && $movie['Runtime'] !== 'N/A')
                <span>⏱ {{ $movie['Runtime'] }}</span>
                <span class="dot">•</span>
            @endif
            @if(!empty($movie['Rated']) && $movie['Rated'] !== 'N/A')
                <span>🎬 {{ $movie['Rated'] }}</span>
            @endif
        </div>

        {{-- Ratings --}}
        @if(!empty($movie['Ratings']))
            <div class="ratings-row">
                @foreach($movie['Ratings'] as $rating)
                    <div class="rating-chip">
                        <div class="rating-source">
                            {{ str_replace(['Internet Movie Database', 'Rotten Tomatoes', 'Metacritic'], ['IMDb', 'RT', 'Meta'], $rating['Source']) }}
                        </div>
                        <div class="rating-value">{{ $rating['Value'] }}</div>
                    </div>
                @endforeach
                @if(!empty($movie['imdbVotes']) && $movie['imdbVotes'] !== 'N/A')
                    <div class="rating-chip">
                        <div class="rating-source">IMDb Votes</div>
                        <div class="rating-value" style="font-size:0.85rem;">{{ $movie['imdbVotes'] }}</div>
                    </div>
                @endif
            </div>
        @endif

        {{-- Favorite Button --}}
        <div class="fav-action" id="favAction">
            @if($isFavorite)
                <button id="favBtn" class="btn btn-fav" onclick="toggleFavorite(this)" data-fav="1"
                    data-id="{{ $movie['imdbID'] }}"
                    data-title="{{ addslashes($movie['Title'] ?? '') }}"
                    data-year="{{ $movie['Year'] ?? '' }}"
                    data-poster="{{ $poster }}"
                    data-type="{{ $movie['Type'] ?? '' }}">
                    ♥ {{ __('app.remove_favorite') }}
                </button>
            @else
                <button id="favBtn" class="btn btn-outline" onclick="toggleFavorite(this)" data-fav="0"
                    data-id="{{ $movie['imdbID'] }}"
                    data-title="{{ addslashes($movie['Title'] ?? '') }}"
                    data-year="{{ $movie['Year'] ?? '' }}"
                    data-poster="{{ $poster }}"
                    data-type="{{ $movie['Type'] ?? '' }}">
                    ♡ {{ __('app.add_favorite') }}
                </button>
            @endif
        </div>

        {{-- Meta grid --}}
        <div class="meta-grid">
            @if(!empty($movie['Genre']) && $movie['Genre'] !== 'N/A')
                <div class="meta-item">
                    <div class="meta-label">{{ __('app.detail_genre') }}</div>
                    <div class="meta-value">{{ $movie['Genre'] }}</div>
                </div>
            @endif
            @if(!empty($movie['Director']) && $movie['Director'] !== 'N/A')
                <div class="meta-item">
                    <div class="meta-label">{{ __('app.detail_director') }}</div>
                    <div class="meta-value">{{ $movie['Director'] }}</div>
                </div>
            @endif
            @if(!empty($movie['Writer']) && $movie['Writer'] !== 'N/A')
                <div class="meta-item">
                    <div class="meta-label">{{ __('app.detail_writer') }}</div>
                    <div class="meta-value">{{ $movie['Writer'] }}</div>
                </div>
            @endif
            @if(!empty($movie['Actors']) && $movie['Actors'] !== 'N/A')
                <div class="meta-item">
                    <div class="meta-label">{{ __('app.detail_actors') }}</div>
                    <div class="meta-value">{{ $movie['Actors'] }}</div>
                </div>
            @endif
            @if(!empty($movie['Released']) && $movie['Released'] !== 'N/A')
                <div class="meta-item">
                    <div class="meta-label">{{ __('app.detail_released') }}</div>
                    <div class="meta-value">{{ $movie['Released'] }}</div>
                </div>
            @endif
            @if(!empty($movie['Language']) && $movie['Language'] !== 'N/A')
                <div class="meta-item">
                    <div class="meta-label">{{ __('app.detail_language') }}</div>
                    <div class="meta-value">{{ $movie['Language'] }}</div>
                </div>
            @endif
            @if(!empty($movie['Country']) && $movie['Country'] !== 'N/A')
                <div class="meta-item">
                    <div class="meta-label">{{ __('app.detail_country') }}</div>
                    <div class="meta-value">{{ $movie['Country'] }}</div>
                </div>
            @endif
            @if(!empty($movie['Awards']) && $movie['Awards'] !== 'N/A')
                <div class="meta-item" style="grid-column: 1 / -1;">
                    <div class="meta-label">🏆 {{ __('app.detail_awards') }}</div>
                    <div class="meta-value">{{ $movie['Awards'] }}</div>
                </div>
            @endif
        </div>

        {{-- Plot --}}
        @if(!empty($movie['Plot']) && $movie['Plot'] !== 'N/A')
            <div class="section-title">{{ __('app.detail_plot') }}</div>
            <div class="plot-text">{{ $movie['Plot'] }}</div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
const CSRF  = document.querySelector('meta[name="csrf-token"]').content;
const lang  = {
    addFav:   '♡ {{ __("app.add_favorite") }}',
    remFav:   '♥ {{ __("app.remove_favorite") }}',
    favAdded: '{{ __("favorites.added") }}',
    favRem:   '{{ __("favorites.removed") }}',
};

async function toggleFavorite(btn) {
    const isFav  = btn.dataset.fav === '1';
    const imdbId = btn.dataset.id;

    if (isFav) {
        const res = await fetch(`/favorites/${imdbId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        });
        if (res.ok) {
            btn.dataset.fav = '0';
            btn.className = 'btn btn-outline';
            btn.textContent = lang.addFav;
            showToast(lang.favRem);
        }
    } else {
        const body = new FormData();
        body.append('imdb_id', imdbId);
        body.append('title', btn.dataset.title);
        body.append('year', btn.dataset.year);
        body.append('poster', btn.dataset.poster);
        body.append('type', btn.dataset.type);
        body.append('_token', CSRF);
        const res = await fetch('/favorites', { method: 'POST', headers: { 'Accept': 'application/json' }, body });
        if (res.ok) {
            btn.dataset.fav = '1';
            btn.className = 'btn btn-fav';
            btn.textContent = lang.remFav;
            showToast(lang.favAdded);
        }
    }
}
</script>
@endpush
