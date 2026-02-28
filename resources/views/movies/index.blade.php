@extends('layouts.app')

@section('title', __('app.search_title'))
@section('meta_description', 'Search and discover thousands of movies and TV series.')

@push('styles')
<style>
    .page-header {
        margin-bottom: 2rem;
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
    .page-sub {
        color: var(--muted);
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    /* Search */
    .search-bar {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 1.25rem 1.5rem;
        display: flex;
        gap: 0.75rem;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 2rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.2);
    }
    .search-input-wrap {
        display: flex;
        flex: 1;
        min-width: 220px;
        position: relative;
    }
    .search-input-wrap svg {
        position: absolute;
        left: 0.875rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted);
    }
    .search-input {
        flex: 1;
        background: var(--card2);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 0.7rem 0.875rem 0.7rem 2.75rem;
        color: var(--text);
        font-size: 0.9rem;
        font-family: inherit;
        outline: none;
        transition: border-color 0.2s;
        width: 100%;
    }
    .search-input:focus { border-color: var(--accent); }
    .search-input::placeholder { color: #4a5068; }
    .search-select {
        background: var(--card2);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 0.7rem 0.875rem;
        color: var(--text);
        font-size: 0.85rem;
        font-family: inherit;
        outline: none;
        cursor: pointer;
    }
    .search-select:focus { border-color: var(--accent); }

    /* Results info */
    .results-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .results-count {
        font-size: 0.85rem;
        color: var(--muted);
    }
    .results-count strong { color: var(--text); }

    /* Movie grid */
    .movies-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1.25rem;
    }

    /* Movie card */
    .movie-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: transform 0.25s, box-shadow 0.25s, border-color 0.25s;
        cursor: pointer;
        position: relative;
        display: flex;
        flex-direction: column;
    }
    .movie-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 40px rgba(0,0,0,0.5);
        border-color: rgba(124,106,247,0.3);
    }
    .movie-card a { text-decoration: none; color: inherit; display: flex; flex-direction: column; flex: 1; }

    /* Poster */
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

    .poster-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(10,10,15,0.95) 0%, transparent 45%);
        opacity: 0;
        transition: opacity 0.3s;
        display: flex;
        align-items: flex-end;
        padding: 0.75rem;
    }
    .movie-card:hover .poster-overlay { opacity: 1; }
    .overlay-actions { display: flex; gap: 0.4rem; width: 100%; }

    .fav-btn-card {
        flex: 1;
        padding: 0.5rem;
        border-radius: 7px;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid rgba(245,158,11,0.4);
        background: rgba(245,158,11,0.12);
        color: var(--gold);
        transition: all 0.2s;
        font-family: inherit;
        text-align: center;
    }
    .fav-btn-card.is-fav {
        border-color: rgba(245,158,11,0.6);
        background: rgba(245,158,11,0.2);
    }
    .fav-btn-card:hover { background: rgba(245,158,11,0.25); }

    /* Card info */
    .card-body {
        padding: 0.75rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    .card-type {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--accent2);
    }
    .card-title-text {
        font-size: 0.875rem;
        font-weight: 600;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .card-year {
        font-size: 0.75rem;
        color: var(--muted);
        margin-top: auto;
    }

    /* Infinite scroll loader */
    #scroll-sentinel {
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 2rem;
    }
    .loader-dots {
        display: flex;
        gap: 6px;
    }
    .loader-dots span {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--accent);
        animation: bounce 0.8s infinite alternate;
    }
    .loader-dots span:nth-child(2) { animation-delay: 0.15s; }
    .loader-dots span:nth-child(3) { animation-delay: 0.3s; }
    @keyframes bounce {
        from { transform: translateY(0); opacity: 0.4; }
        to   { transform: translateY(-10px); opacity: 1; }
    }

    /* Welcome / empty states */
    .empty-state, .welcome-state {
        text-align: center;
        padding: 5rem 2rem;
        display: none;
    }
    .empty-state.visible { display: block; }
    .welcome-state.visible { display: block; }
    .empty-icon { font-size: 4rem; margin-bottom: 1rem; }
    .empty-title { font-size: 1.35rem; font-weight: 700; margin-bottom: 0.5rem; }
    .empty-sub { color: var(--muted); font-size: 0.875rem; margin-bottom: 1.5rem; }
    .suggestions { display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: center; margin-top: 1rem; }
    .suggest-chip {
        background: var(--card2);
        border: 1px solid var(--border);
        border-radius: 999px;
        padding: 0.4rem 0.9rem;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s;
        color: var(--muted);
    }
    .suggest-chip:hover { border-color: var(--accent); color: var(--accent2); background: rgba(124,106,247,0.1); }

    @media (max-width: 640px) {
        .movies-grid { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 0.875rem; }
        .search-bar { flex-direction: column; }
        .search-input-wrap { min-width: unset; width: 100%; }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">{{ __('app.search_title') }}</h1>
    @if($query && $total > 0)
    <p class="page-sub">{{ __('app.results_found', ['count' => number_format($total)]) }}</p>
    @endif
</div>

{{-- Search Bar --}}
<form id="searchForm" class="search-bar" autocomplete="off">
    <div class="search-input-wrap">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input
            type="text"
            id="searchQ"
            name="q"
            class="search-input"
            placeholder="{{ __('app.search_ph') }}"
            value="{{ $query }}"
        >
    </div>
    <select id="searchType" name="type" class="search-select">
        <option value="" {{ $type === '' ? 'selected' : '' }}>{{ __('app.search_type_all') }}</option>
        <option value="movie" {{ $type === 'movie' ? 'selected' : '' }}>{{ __('app.search_movie') }}</option>
        <option value="series" {{ $type === 'series' ? 'selected' : '' }}>{{ __('app.search_series') }}</option>
        <option value="episode" {{ $type === 'episode' ? 'selected' : '' }}>{{ __('app.search_episode') }}</option>
    </select>
    <input
        type="number"
        id="searchYear"
        name="year"
        class="search-select"
        placeholder="{{ __('app.search_year') }}"
        value="{{ $year }}"
        min="1900"
        max="{{ date('Y') }}"
        style="width:100px;"
    >
    <button type="submit" class="btn btn-primary">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        {{ __('app.search_btn') }}
    </button>
</form>

{{-- Results meta --}}
<div class="results-meta" id="resultsMeta" style="{{ (!$query || $error) ? 'display:none' : '' }}">
    <span class="results-count" id="resultsCount">
        <strong>{{ number_format($total) }}</strong> {{ __('app.results_found', ['count' => '']) }}
    </span>
</div>

{{-- Movie Grid --}}
<div class="movies-grid" id="moviesGrid">
    @foreach($movies as $movie)
        @include('movies._card', ['movie' => $movie, 'favoriteIds' => $favoriteIds])
    @endforeach
</div>

{{-- Welcome state (no query yet) --}}
<div class="welcome-state {{ !$query ? 'visible' : '' }}" id="welcomeState">
    <div class="empty-icon">🔍</div>
    <div class="empty-title">{{ __('app.search_title') }}</div>
    <div class="empty-sub">{{ __('app.search_ph') }}</div>
    <div class="suggestions">
        @foreach(['Batman', 'Avengers', 'Spider-Man', 'Inception', 'Interstellar', 'The Dark Knight', 'Parasite', 'Joker'] as $s)
            <button class="suggest-chip" onclick="quickSearch('{{ $s }}')">{{ $s }}</button>
        @endforeach
    </div>
</div>

{{-- Empty state (query returned no results) --}}
<div class="empty-state {{ ($query && ($error || empty($movies))) ? 'visible' : '' }}" id="emptyState">
    <div class="empty-icon">🎬</div>
    <div class="empty-title">{{ __('app.no_results') }}</div>
    <div class="empty-sub">{{ __('app.no_results_sub') }}</div>
</div>

{{-- Infinite scroll sentinel --}}
<div id="scroll-sentinel">
    <div class="loader-dots" id="loaderDots" style="display:none;">
        <span></span><span></span><span></span>
    </div>
</div>
@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const SEARCH_URL = '/movies/search';
const FAV_STORE  = '/favorites';
const FAV_BASE   = '/favorites/';
const lang = {
    addFav:    '{{ __("app.add_favorite") }}',
    removeFav: '{{ __("app.remove_favorite") }}',
    noResults: '{{ __("app.no_results") }}',
    loading:   '{{ __("app.load_more") }}',
    favAdded:  '{{ __("favorites.added") }}',
    favRem:    '{{ __("favorites.removed") }}',
};

let currentPage  = 1;
let currentQuery = document.getElementById('searchQ').value;
let currentType  = document.getElementById('searchType').value;
let currentYear  = document.getElementById('searchYear').value;
let isLoading    = false;
let hasMore      = {{ $hasMore ? 'true' : 'false' }};
let totalResults = {{ $total }};

const grid       = document.getElementById('moviesGrid');
const emptyState  = document.getElementById('emptyState');
const welcomeState= document.getElementById('welcomeState');
const sentinel   = document.getElementById('scroll-sentinel');
const loader     = document.getElementById('loaderDots');
const resCount   = document.getElementById('resultsCount');
const resMeta    = document.getElementById('resultsMeta');

// ── Favorite toggle ──────────────────────────────────────────
document.addEventListener('click', async e => {
    const btn = e.target.closest('.fav-btn-card');
    if (!btn) return;
    e.preventDefault();
    e.stopPropagation();

    const isFav = btn.dataset.fav === '1';
    const imdbId  = btn.dataset.id;
    const title   = btn.dataset.title;
    const year    = btn.dataset.year;
    const poster  = btn.dataset.poster;
    const type    = btn.dataset.type;

    if (isFav) {
        const res = await fetch(FAV_BASE + imdbId, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        });
        if (res.ok) {
            btn.dataset.fav = '0';
            btn.classList.remove('is-fav');
            btn.textContent = '♡ ' + lang.addFav;
            showToast(lang.favRem, 'success');
        }
    } else {
        const body = new FormData();
        body.append('imdb_id', imdbId);
        body.append('title', title);
        body.append('year', year);
        body.append('poster', poster);
        body.append('type', type);
        body.append('_token', CSRF);
        const res = await fetch(FAV_STORE, { method: 'POST', headers: { 'Accept': 'application/json' }, body });
        if (res.ok) {
            btn.dataset.fav = '1';
            btn.classList.add('is-fav');
            btn.textContent = '♥ ' + lang.removeFav;
            showToast(lang.favAdded, 'success');
        }
    }
});

// ── Search form ──────────────────────────────────────────────
document.getElementById('searchForm').addEventListener('submit', e => {
    e.preventDefault();
    currentQuery = document.getElementById('searchQ').value.trim();
    currentType  = document.getElementById('searchType').value;
    currentYear  = document.getElementById('searchYear').value;
    currentPage  = 0;
    hasMore      = true;
    grid.innerHTML = '';
    emptyState.classList.remove('visible');
    welcomeState.classList.remove('visible');
    if (!currentQuery) { welcomeState.classList.add('visible'); return; }
    loadMore();
});

// ── Quick search chips ───────────────────────────────────────
function quickSearch(term) {
    document.getElementById('searchQ').value = term;
    document.getElementById('searchForm').dispatchEvent(new Event('submit'));
}

// ── Infinite scroll (IntersectionObserver) ───────────────────
const observer = new IntersectionObserver(entries => {
    if (entries[0].isIntersecting && hasMore && !isLoading) {
        loadMore();
    }
}, { rootMargin: '200px' });

observer.observe(sentinel);

async function loadMore() {
    if (isLoading || !hasMore || !currentQuery) return;
    isLoading = true;
    currentPage++;
    if (loader) loader.style.display = 'flex';

    try {
        const params = new URLSearchParams({
            q: currentQuery, page: currentPage,
            type: currentType, year: currentYear
        });
        const res  = await fetch(SEARCH_URL + '?' + params, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
        const data = await res.json();

        hasMore      = data.hasMore;
        totalResults = data.total;

        if (data.movies && data.movies.length > 0) {
            data.movies.forEach(m => grid.insertAdjacentHTML('beforeend', buildCard(m)));
            initLazyLoad();
        }

        if (data.total === 0 || (currentPage === 1 && (!data.movies || !data.movies.length))) {
            emptyState.classList.add('visible');
            resMeta.style.display = 'none';
        } else {
            emptyState.classList.remove('visible');
            resMeta.style.display = '';
            resCount.innerHTML = `<strong>${data.total.toLocaleString()}</strong> results found`;
        }

        if (!hasMore && loader) loader.style.display = 'none';
    } catch (err) {
        console.error(err);
    } finally {
        isLoading = false;
        if (loader && !hasMore) loader.style.display = 'none';
    }
}

function buildCard(m) {
    const poster = m.Poster && m.Poster !== 'N/A' ? m.Poster : '';
    const favLabel = m.isFavorite ? `♥ ${lang.removeFav}` : `♡ ${lang.addFav}`;
    const favClass = m.isFavorite ? 'is-fav' : '';
    const posterHtml = poster
        ? `<img class="poster-img lazy" data-src="${poster}" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt="${escHtml(m.Title)}" loading="lazy">`
        : `<div class="poster-placeholder"><span>🎬</span><span>No Image</span></div>`;

    return `
    <div class="movie-card">
        <a href="/movies/${m.imdbID}">
            <div class="poster-wrap">
                ${posterHtml}
                <div class="poster-overlay">
                    <div class="overlay-actions">
                        <button class="fav-btn-card ${favClass}"
                            data-id="${m.imdbID}"
                            data-fav="${m.isFavorite ? 1 : 0}"
                            data-title="${escHtml(m.Title)}"
                            data-year="${m.Year || ''}"
                            data-poster="${poster}"
                            data-type="${m.Type || ''}"
                        >${favLabel}</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="card-type">${m.Type || 'movie'}</div>
                <div class="card-title-text">${escHtml(m.Title)}</div>
                <div class="card-year">${m.Year || '—'}</div>
            </div>
        </a>
    </div>`;
}

function escHtml(s) {
    const d = document.createElement('div');
    d.textContent = s || '';
    return d.innerHTML;
}

// ── Lazy load images ─────────────────────────────────────────
function initLazyLoad() {
    const imgs = document.querySelectorAll('img.lazy');
    if ('IntersectionObserver' in window) {
        const imgObserver = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    obs.unobserve(img);
                }
            });
        }, { rootMargin: '100px' });
        imgs.forEach(img => imgObserver.observe(img));
    } else {
        imgs.forEach(img => { img.src = img.dataset.src; img.classList.remove('lazy'); });
    }
}
initLazyLoad();
</script>
@endpush
