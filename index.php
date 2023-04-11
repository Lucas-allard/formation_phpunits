<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/src/Exception/exception.php';

use App\Logger\Logger;

$logger = new Logger();

$logger->info('Hello World', ['name' => 'John Doe']);


