<?php

namespace App\Services;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class Logger
{
    private static ?MonologLogger $instance = null;

    public static function getInstance(): MonologLogger
    {
        if (self::$instance === null) {
            self::$instance = new MonologLogger('app');
            
            // Create stdout handler
            $streamHandler = new StreamHandler('php://stdout', MonologLogger::DEBUG);
            
            // Set custom format for better readability
            $formatter = new LineFormatter(
                "[%datetime%] %level_name%: %message% %context%\n",
                'Y-m-d H:i:s'
            );
            $streamHandler->setFormatter($formatter);
            
            self::$instance->pushHandler($streamHandler);
        }
        
        return self::$instance;
    }
}