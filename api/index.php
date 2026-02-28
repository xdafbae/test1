<?php

/**
 * Vercel PHP entry point for Laravel.
 *
 * Vercel uses a read-only filesystem — writable directories are
 * redirected to /tmp so Laravel can write sessions, cache, and views.
 */

define('LARAVEL_START', microtime(true));

// ── 1. Create writable dirs in /tmp ──────────────────────────────────────
$tmpBase = sys_get_temp_dir() . '/laravel';
foreach ([
    $tmpBase . '/storage/framework/sessions',
    $tmpBase . '/storage/framework/cache/data',
    $tmpBase . '/storage/framework/views',
    $tmpBase . '/storage/logs',
    $tmpBase . '/bootstrap/cache',
] as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
}

// ── 2. Serve static files from /public directly ──────────────────────────
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$staticFile = __DIR__ . '/../public' . $uri;
if ($uri !== '/' && file_exists($staticFile) && !is_dir($staticFile)) {
    return false;
}

// ── 3. Boot Laravel ───────────────────────────────────────────────────────
require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

// Redirect storage to /tmp (must be before kernel handles request)
$app->useStoragePath($tmpBase . '/storage');

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();

$kernel->terminate($request, $response);
