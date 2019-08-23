<?php

use Hackathon\Util\Migration;

require_once __DIR__ . "/db/init.php";

return [
    'migration_base_class' => Migration::class,
    'paths' => [
        'migrations' => __DIR__ . '/db/migrations',
        'seeds' => __DIR__ . '/db/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'migrations_log',
        'default_database' => 'current',
        'current' => [
            'adapter' => 'pgsql',
            'host' => \getenv("DB_HOST"),
            'name' => \getenv("DB_NAME"),
            'user' => \getenv("DB_USER"),
            'pass' => \getenv("DB_PASS"),
            'port' => \getenv("DB_PORT"),
            'charset' => 'utf8',
        ],
    ],
];
