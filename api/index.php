<?php

/**
 * Vercel PHP entry point for Laravel.
 *
 * Uses putenv() to redirect writable paths to /tmp BEFORE Laravel boots,
 * avoiding useStoragePath() which breaks ViewServiceProvider binding.
 */

define('LARAVEL_START', microtime(true));

// ── 1. Set up /tmp writable directories for Vercel read-only filesystem ──
$tmpBase = sys_get_temp_dir() . '/laravel';

$viewsDir = $tmpBase . '/views';
if (!is_dir($viewsDir)) {
    mkdir($viewsDir, 0775, true);
}

// Tell Laravel where to compile Blade views (via config/view.php)
putenv('VIEW_COMPILED_PATH=' . $viewsDir);
$_ENV['VIEW_COMPILED_PATH']    = $viewsDir;
$_SERVER['VIEW_COMPILED_PATH'] = $viewsDir;

// ── 2. Serve static files from /public directly ──────────────────────────
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$staticFile = __DIR__ . '/../public' . $uri;
if ($uri !== '/' && file_exists($staticFile) && !is_dir($staticFile)) {
    return false;
}

// ── 3. Boot Laravel ───────────────────────────────────────────────────────
try {
    if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
        throw new RuntimeException('vendor/autoload.php not found. Run composer install.');
    }

    require_once __DIR__ . '/../vendor/autoload.php';

    $app = require_once __DIR__ . '/../bootstrap/app.php';

    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    )->send();
    $kernel->terminate($request, $response);

} catch (\Throwable $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'error'    => $e->getMessage(),
        'class'    => get_class($e),
        'file'     => str_replace(dirname(__DIR__), '', $e->getFile()),
        'line'     => $e->getLine(),
        'trace'    => array_slice(
            array_map(fn($l) => str_replace(dirname(__DIR__), '', $l),
                explode("\n", $e->getTraceAsString())
            ), 0, 8
        ),
        'php'      => PHP_VERSION,
        'env_key'  => !empty($_ENV['APP_KEY']) ? 'SET ('.strlen($_ENV['APP_KEY']).' chars)' : 'NOT SET',
        'views_ok' => is_writable($viewsDir),
    ], JSON_PRETTY_PRINT);
}
