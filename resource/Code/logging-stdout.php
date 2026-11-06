<?php

use Monolog\Handler\StreamHandler;
use Monolog\Processor\PsrLogMessageProcessor;

return [
    // Default Log Channel diarahkan ke stdout agar dapat ditangkap oleh container runtime
    'default' => env('LOG_CHANNEL', 'stdout'),

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => explode(',', (string) env('LOG_STACK', 'stdout')),
            'ignore_exceptions' => false,
        ],

        'stdout' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDOUT_FORMATTER'),
            'with' => [
                'stream' => 'php://stdout',
            ],
            'processors' => [PsrLogMessageProcessor::class],
        ],
    ],
];
