<?php
declare(strict_types=1);

function eging_private_root(): string
{
    $documentRoot = rtrim((string)($_SERVER['DOCUMENT_ROOT'] ?? ''), "/\\");
    if ($documentRoot !== '') {
        $productionRoot = dirname($documentRoot) . DIRECTORY_SEPARATOR . 'eging-private';
        if (is_file($productionRoot . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'bootstrap.php')) {
            return $productionRoot;
        }
    }

    $localRoot = dirname(__DIR__);
    if (is_file($localRoot . '/app/bootstrap.php')) {
        return $localRoot;
    }

    throw new RuntimeException('EGING private runtime is not available.');
}

function eging_private_path(string $relative): string
{
    return eging_private_root() . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, ltrim($relative, '/\\'));
}

function eging_config_file(): string
{
    return eging_private_path('config/config.php');
}

function eging_schema_file(): string
{
    return eging_private_path('database/schema.sql');
}

function eging_require_bootstrap(): void
{
    require_once eging_private_path('app/bootstrap.php');
}
