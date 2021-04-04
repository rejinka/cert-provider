<?php
declare(strict_types = 1);

use App\Infrastructure\Symfony\Kernel;

require_once __DIR__ . '/../config/bootstrap.php';

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$kernel->boot();

$warmer = $kernel->getContainer()
    ->get('cache_warmer')
    ->warmUp($kernel->getCacheDir());
