<?php

require 'vendor/autoload.php';

use App\Services\Logger;

$logger = Logger::getInstance();

$logger->debug('This is a debug message');
$logger->info('This is an info message');
$logger->warning('This is a warning message');  
$logger->error('This is an error message');

echo "Logger test completed - check output above\n";