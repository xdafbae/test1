<?php

/**
 * Vercel PHP entry point for Laravel (with debug output).
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

// ── 3. Boot Laravel with full error output ────────────────────────────────
try {
    if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
        throw new RuntimeException('vendor/autoload.php not found. Run composer install.');
    }

    require_once __DIR__ . '/../vendor/autoload.php';

    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->useStoragePath($tmpBase . '/storage');

    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    )->send();
    $kernel->terminate($request, $response);

} catch (\Throwable $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'error'   => $e->getMessage(),
        'class'   => get_class($e),
        'file'    => str_replace(dirname(__DIR__), '', $e->getFile()),
        'line'    => $e->getLine(),
        'trace'   => array_slice(
            array_map(fn($l) => str_replace(dirname(__DIR__), '', $l),
                explode("\n", $e->getTraceAsString())
            ), 0, 8
        ),
        'php'     => PHP_VERSION,
        'tmp_ok'  => is_writable(sys_get_temp_dir()),
        'vendor'  => file_exists(__DIR__ . '/../vendor/autoload.php'),
        'env_key' => !empty($_ENV['APP_KEY']) ? 'SET ('.strlen($_ENV['APP_KEY']).' chars)' : 'NOT SET',
    ], JSON_PRETTY_PRINT);
}
