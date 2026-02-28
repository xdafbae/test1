@extends('layouts.app')

@section('title', __('app.favorites_title'))
@section('meta_description', 'Your saved favorite movies and TV series.')

@push('styles')
<style>
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .page-title {
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        background: linear-gradient(135deg, #fff 40%, #94a3b8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .fav-count {
        font-size: 0.8rem;
        color: var(--muted);
    }

    /* Grid (re-use movies grid styles) */
    .movies-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1.25rem;
    }

    .movie-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: transform 0.25s, box-shadow 0.25s, border-color 0.25s;
        position: relative;
        display: flex;
        flex-direction: column;
    }
    .movie-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 40px rgba(0,0,0,0.5);
        border-color: rgba(124,106,247,0.3);
    }
    .card-link { text-decoration: none; color: inherit; display: flex; flex-direction: column; flex: 1; }

    .poster-wrap {
        position: relative;
        aspect-ratio: 2/3;
        overflow: hidden;
        background: var(--card2);
    }
    .poster-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s;
        display: block;
    }
    .movie-card:hover .poster-img { transform: scale(1.05); }
    .poster-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--muted);
        font-size: 2.5rem;
        gap: 0.5rem;
        background: linear-gradient(135deg, var(--card2), #0e0e1a);
    }
    .poster-placeholder span { font-size: 0.7rem; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; }

    .remove-btn {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: rgba(239,68,68,0.85);
        border: none;
        color: #fff;
        font-size: 0.85rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s, transform 0.2s;
        backdrop-filter: blur(4px);
    }
    .movie-card:hover .remove-btn { opacity: 1; }
    .remove-btn:hover { transform: scale(1.1); background: var(--danger); }

    .card-body {
        padding: 0.75rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    .card-type { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: var(--accent2); }
    .card-title-text {
        font-size: 0.875rem;
        font-weight: 600;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .card-year { font-size: 0.75rem; color: var(--muted); margin-top: auto; }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 6rem 2rem;
    }
    .empty-icon { font-size: 5rem; margin-bottom: 1rem; }
    .empty-title { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem; }
    .empty-sub { color: var(--muted); margin-bottom: 1.75rem; }

    @media (max-width: 640px) {
        .movies-grid { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 0.875rem; }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ __('app.favorites_title') }}</h1>
        @if($favorites->count() > 0)
            <div class="fav-count">{{ $favorites->count() }} {{ Str::plural('movie', $favorites->count()) }}</div>
        @endif
    </div>
    <a href="{{ route('movies.index') }}" class="btn btn-outline">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        {{ __('app.browse_movies') }}
    </a>
</div>

@if($favorites->isEmpty())
    <div class="empty-state">
        <div class="empty-icon">💔</div>
        <div class="empty-title">{{ __('app.favorites_empty') }}</div>
        <div class="empty-sub">{{ __('app.favorites_empty_sub') }}</div>
        <a href="{{ route('movies.index') }}" class="btn btn-primary">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            {{ __('app.browse_movies') }}
        </a>
    </div>
@else
    <div class="movies-grid" id="favGrid">
        @foreach($favorites as $fav)
            @php $poster = ($fav->poster && $fav->poster !== 'N/A') ? $fav->poster : ''; @endphp
            <div class="movie-card" id="fav-{{ $fav->imdb_id }}">
                <a class="card-link" href="{{ route('movies.show', $fav->imdb_id) }}">
                    <div class="poster-wrap">
                        @if($poster)
                            <img class="poster-img" src="{{ $poster }}" alt="{{ $fav->title }}" loading="lazy">
                        @else
                            <div class="poster-placeholder">
                                <span>🎬</span>
                                <span>No Image</span>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="card-type">{{ $fav->type ?? 'movie' }}</div>
                        <div class="card-title-text">{{ $fav->title }}</div>
                        <div class="card-year">{{ $fav->year ?? '—' }}</div>
                    </div>
                </a>
                <button class="remove-btn" onclick="removeFav('{{ $fav->imdb_id }}', this)" title="{{ __('app.remove_favorite') }}">✕</button>
            </div>
        @endforeach
    </div>
@endif
@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const lang = {
    removed: '{{ __("favorites.removed") }}',
};

async function removeFav(imdbId, btn) {
    btn.disabled = true;
    const res = await fetch(`/favorites/${imdbId}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
    });
    if (res.ok) {
        const card = document.getElementById('fav-' + imdbId);
        card.style.transition = 'opacity 0.3s, transform 0.3s';
        card.style.opacity = '0';
        card.style.transform = 'scale(0.92)';
        setTimeout(() => {
            card.remove();
            showToast(lang.removed);
            // Show empty state if no more cards
            if (!document.querySelector('.movie-card')) {
                document.getElementById('favGrid').innerHTML =
                    `<div style="grid-column:1/-1;text-align:center;padding:4rem 2rem;">
                        <div style="font-size:4rem;margin-bottom:1rem;">💔</div>
                        <div style="font-size:1.25rem;font-weight:700;">{{ __('app.favorites_empty') }}</div>
                        <p style="color:var(--muted);margin:0.5rem 0 1.5rem;">{{ __('app.favorites_empty_sub') }}</p>
                        <a href="{{ route('movies.index') }}" class="btn btn-primary">{{ __('app.browse_movies') }}</a>
                    </div>`;
            }
        }, 320);
    } else {
        btn.disabled = false;
    }
}
</script>
@endpush
