<?php

/**
 * Vercel PHP entry point for Laravel.
 */

define('LARAVEL_START', microtime(true));

// ── 1. Set up /tmp writable directories ──────────────────────────────────
$tmpBase = sys_get_temp_dir() . '/laravel';
$viewsDir = $tmpBase . '/views';
$logsDir = $tmpBase . '/logs';

if (!is_dir($viewsDir)) {
    mkdir($viewsDir, 0775, true);
}
if (!is_dir($logsDir)) {
    mkdir($logsDir, 0775, true);
}

putenv('VIEW_COMPILED_PATH=' . $viewsDir);
$_ENV['VIEW_COMPILED_PATH']    = $viewsDir;
$_SERVER['VIEW_COMPILED_PATH'] = $viewsDir;

// Force log to /tmp for debugging
putenv('LOG_CHANNEL=daily');
putenv('LOG_DIR=' . $logsDir);
$_ENV['LOG_DIR'] = $logsDir;
$_SERVER['LOG_DIR'] = $logsDir;
$app['config']->set('logging.channels.daily.path', $logsDir.'/laravel.log');

// ── 2. Serve static files from /public directly ──────────────────────────
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$staticFile = __DIR__ . '/../public' . $uri;
if ($uri !== '/' && file_exists($staticFile) && !is_dir($staticFile)) {
    return false;
}

// ── 3. Boot Laravel ───────────────────────────────────────────────────────
try {
    require_once __DIR__ . '/../vendor/autoload.php';

    $app = require_once __DIR__ . '/../bootstrap/app.php';

    // Override log path just in case
    $app->useLogPath($logsDir);

    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    )->send();
    $kernel->terminate($request, $response);

} catch (\Throwable $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    
    // Read the log file to find the REAL original error
    $logContent = 'No log file found.';
    $logFile = clone $app ? $app->storagePath('logs/laravel.log') : $logsDir . '/laravel.log';
    if (file_exists($logFile)) {
        $logContent = file_get_contents($logFile);
    } elseif (file_exists($logsDir . '/laravel.log')) {
        $logContent = file_get_contents($logsDir . '/laravel.log');
    }

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
        'try_catch_chain' => $errors,
        'laravel_log' => substr($logContent, -2000), // Last 2000 chars of the log
    ], JSON_PRETTY_PRINT);
}
