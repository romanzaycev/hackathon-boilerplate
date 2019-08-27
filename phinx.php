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
            'name' => \getenv("DB_DATABASE"),
            'user' => \getenv("DB_USERNAME"),
            'pass' => \getenv("DB_PASSWORD"),
            'port' => \getenv("DB_PORT"),
            'charset' => 'utf8',
        ],
    ],
];
