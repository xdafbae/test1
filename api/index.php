<?php

/**
 * Vercel PHP entry point for Laravel.
 */

define('LARAVEL_START', microtime(true));

// ── 1. Set up /tmp writable directories ──────────────────────────────────
$tmpBase = sys_get_temp_dir() . '/laravel';
$viewsDir = $tmpBase . '/views';

if (!is_dir($viewsDir)) {
    mkdir($viewsDir, 0775, true);
}

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
require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

// Prevent Route and Config Caching issues in serverless
putenv('APP_CONFIG_CACHE=/tmp/config.php');
putenv('APP_EVENTS_CACHE=/tmp/events.php');
putenv('APP_PACKAGES_CACHE=/tmp/packages.php');
putenv('APP_ROUTES_CACHE=/tmp/routes.php');
putenv('APP_SERVICES_CACHE=/tmp/services.php');

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();

$kernel->terminate($request, $response);
