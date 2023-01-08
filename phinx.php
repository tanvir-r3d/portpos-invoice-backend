<?php

require_once './vendor/autoload.php';

return
    [
        'paths' => [
            'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
            'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
        ],
        'environments' => [
            'default_migration_table' => 'phinxlog',
            'default_environment' => 'development',
            'development' => [
                'adapter' => 'mysql',
                'host' => 'database',
                'name' => 'portpos-backend',
                'user' => 'root',
                'pass' => '@@@2023',
                'port' => 3306,
                'charset' => 'utf8',
            ],
        ],
        'version_order' => 'creation'
    ];
