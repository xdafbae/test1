<?php

/**
 * Vercel PHP entry point for Laravel.
 * All HTTP requests are forwarded here by vercel.json routes.
 */

// Serve static files from /public directly
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

$staticFile = __DIR__ . '/../public' . $uri;
if ($uri !== '/' && file_exists($staticFile) && !is_dir($staticFile)) {
    return false;
}

// Boot Laravel
define('LARAVEL_START', microtime(true));

require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();

$kernel->terminate($request, $response);
