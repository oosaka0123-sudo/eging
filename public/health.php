<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$dbStatus = 'not_configured';
try {
    require __DIR__ . '/_runtime.php';
    eging_require_bootstrap();
    $dbStatus = 'ok';
} catch (Throwable $e) {
    $dbStatus = 'connection_failed';

    $cursor = $e;
    while ($cursor !== null) {
        if ($cursor instanceof PDOException) {
            $driverCode = isset($cursor->errorInfo[1]) ? (int)$cursor->errorInfo[1] : 0;
            $dbStatus = match ($driverCode) {
                1045 => 'auth_failed',
                1049 => 'db_not_found',
                2002, 2003, 2005 => 'connection_failed',
                default => 'db_error',
            };
            break;
        }
        $cursor = $cursor->getPrevious();
    }

    if ($dbStatus === 'connection_failed' && str_contains($e->getMessage(), 'private runtime is not available')) {
        $dbStatus = 'runtime_missing';
    }
}

echo json_encode([
    'ok' => true,
    'app' => 'eging-gear-lab',
    'php' => PHP_VERSION,
    'db' => $dbStatus,
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
