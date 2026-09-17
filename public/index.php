<?php

declare(strict_types=1);

use Portfolio\Core\App;

// Compute the path that maps to BASE_PATH (used to strip from request URI).
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
$_SERVER['BASE_PATH'] = rtrim(rawurldecode($scriptDir), '/');

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/app/Core/App.php';

App::boot();
App::dispatch();