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
try {
    require_once __DIR__ . '/../vendor/autoload.php';

    // Catch ANY exception during the framework lifecycle, including hidden ones inside ExceptionHandler
    set_exception_handler(function ($e) {
        http_response_code(500);
        header('Content-Type: application/json');
        
        $errors = [];
        $current = $e;
        while ($current) {
            $errors[] = [
                'message' => $current->getMessage(),
                'class'   => get_class($current),
                'file'    => str_replace(dirname(__DIR__), '', $current->getFile()),
                'line'    => $current->getLine(),
            ];
            $current = $current->getPrevious();
        }

        echo json_encode([
            'fatal_chain' => $errors,
        ], JSON_PRETTY_PRINT);
        exit;
    });

    $app = require_once __DIR__ . '/../bootstrap/app.php';

    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    )->send();
    $kernel->terminate($request, $response);

} catch (\Throwable $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    $errors = [];
    $current = $e;
    while ($current) {
        $errors[] = [
            'message' => $current->getMessage(),
            'class'   => get_class($current),
            'file'    => str_replace(dirname(__DIR__), '', $current->getFile()),
            'line'    => $current->getLine(),
        ];
        $current = $current->getPrevious();
    }
    echo json_encode(['try_catch_chain' => $errors], JSON_PRETTY_PRINT);
}
