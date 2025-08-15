<?php

declare(strict_types=1);

$projectRoot = dirname(__DIR__);

require $projectRoot . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeImmutable($projectRoot);
$dotenv->safeLoad();
