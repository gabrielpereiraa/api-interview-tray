<?php

return [
    'default' => env('QUEUE_CONNECTION', 'rabbitmq'),

    'connections' => [
        'rabbitmq' => [
            'driver' => 'rabbitmq',
            'queue' => env('RABBITMQ_QUEUE', 'default'),
            'connection' => PhpAmqpLib\Connection\AMQPStreamConnection::class,
            'hosts' => [
                [
                    'host' => env('RABBITMQ_HOST', 'rabbitmq'),
                    'port' => env('RABBITMQ_PORT', 5672),
                    'user' => env('RABBITMQ_USER', 'guest'),
                    'password' => env('RABBITMQ_PASSWORD', 'guest'),
                    'vhost' => env('RABBITMQ_VHOST', '/'),
                ],
            ],
            'options' => [
                'exchange' => [
                    'name' => env('RABBITMQ_EXCHANGE_NAME', 'application-x'),
                    'type' => env('RABBITMQ_EXCHANGE_TYPE', 'direct'),
                    'declare' => env('RABBITMQ_EXCHANGE_DECLARE', true),
                ],
            ],
        ],
    ]
];