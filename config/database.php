<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'buroflow'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'mysql_tenant' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_TENANT', '127.0.0.1'),
            'port' => env('DB_PORT_TENANT', '3306'),
            'database' => env('DB_DATABASE_TENANT', null),
            'username' => env('DB_USERNAME_TENANT', 'root'),
            'password' => env('DB_PASSWORD_TENANT', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'tenant' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_TENANT', '127.0.0.1'),
            'port' => env('DB_PORT_TENANT', '3306'),
            'database' => env('DB_DATABASE_TENANT', null),
            'username' => env('DB_USERNAME_TENANT', 'root'),
            'password' => env('DB_PASSWORD_TENANT', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ],
        
        'radiusLive' => [
            'driver' => 'mysql',
                'host' => env('DB_HOST_RADIUS_LIVE', '45.123.107.4'),
                'port' => env('DB_PORT_RADIUS__LIVE', '23306'),
            'database' => env('DB_DATABASE_RADIUS_LIVE', 'radius'),
                'username' => env('DB_USERNAME_RADIUS_LIVE', 'root'),
                'password' => env('DB_PASSWORD_RADIUS_LIVE', 'Nwlcmtthjngl!1'),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => true,
                'engine' => null,
                'options' => extension_loaded('pdo_mysql') ? array_filter([
                    PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                ]) : [],
            ],
    
        'radiusTest' => [
            'driver' => 'mysql',
                'host' => env('DB_HOST_RADIUS_TEST', '45.123.107.12'),
                'port' => env('DB_PORT_RADIUS_TEST', '13306'),
            'database' => env('DB_DATABASE_RADIUS_TEST', 'radius'),
                'username' => env('DB_USERNAME_RADIUS_TEST', 'root'),
                'password' => env('DB_PASSWORD_RADIUS_TEST', '1qazXSW@3edc'),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => true,
                'engine' => null,
                'options' => extension_loaded('pdo_mysql') ? array_filter([
                    PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                ]) : [],
            ],
        
        'radius' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_RADIUS', '45.123.107.4'),
            'port' => env('DB_PORT_RADIUS', '13306'),
            'database' => env('DB_DATABASE_RADIUS', 'radius'),
            'username' => env('DB_USERNAME_RADIUS', 'root'),
            'password' => env('DB_PASSWORD_RADIUS', 'Nwlcmtthjngl!1'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'imsTest' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_IMS_TEST', '45.123.107.12'),
            'port' => env('DB_PORT_IMS_TEST', '13306'),
            'database' => env('DB_DATABASE_IMS_TEST', 'ims'),
            'username' => env('DB_USERNAME_IMS_TEST', 'root'),
            'password' => env('DB_PASSWORD_IMS_TEST', '1qazXSW@3edc'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'imsLive' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_IMS_LIVE', '45.123.107.4'),
            'port' => env('DB_PORT_IMS_LIVE', '23306'),
            'database' => env('DB_DATABASE_IMS_LIVE', 'ims'),
            'username' => env('DB_USERNAME_IMS_LIVE', 'root'),
            'password' => env('DB_PASSWORD_IMS_LIVE', 'Nwlcmtthjngl!1'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        
        'radiusLive' => [
            'driver' => 'mysql',
                'host' => env('DB_HOST_RADIUS_LIVE', '45.123.107.4'),
                'port' => env('DB_PORT_RADIUS__LIVE', '23306'),
            'database' => env('DB_DATABASE_RADIUS_LIVE', 'radius'),
                'username' => env('DB_USERNAME_RADIUS_LIVE', 'root'),
                'password' => env('DB_PASSWORD_RADIUS_LIVE', 'Nwlcmtthjngl!1'),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => true,
                'engine' => null,
                'options' => extension_loaded('pdo_mysql') ? array_filter([
                    PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                ]) : [],
            ],
    
        'radiusTest' => [
            'driver' => 'mysql',
                'host' => env('DB_HOST_RADIUS_TEST', '45.123.107.12'),
                'port' => env('DB_PORT_RADIUS_TEST', '13306'),
            'database' => env('DB_DATABASE_RADIUS_TEST', 'radius'),
                'username' => env('DB_USERNAME_RADIUS_TEST', 'buroflow2'),
                'password' => env('DB_PASSWORD_RADIUS_TEST', '#WhyNotMe2#'),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => true,
                'engine' => null,
                'options' => extension_loaded('pdo_mysql') ? array_filter([
                    PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                ]) : [],
            ],
        
        'radius' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_RADIUS', '45.123.107.4'),
            'port' => env('DB_PORT_RADIUS', '13306'),
            'database' => env('DB_DATABASE_RADIUS', 'radius'),
            'username' => env('DB_USERNAME_RADIUS', 'root'),
            'password' => env('DB_PASSWORD_RADIUS', 'Nwlcmtthjngl!1'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'imsTest' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_IMS_TEST', '45.123.107.12'),
            'port' => env('DB_PORT_IMS_TEST', '13306'),
            'database' => env('DB_DATABASE_IMS_TEST', 'ims'),
            'username' => env('DB_USERNAME_IMS_TEST', 'root'),
            'password' => env('DB_PASSWORD_IMS_TEST', '1qazXSW@3edc'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'imsLive' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_IMS_LIVE', '45.123.107.4'),
            'port' => env('DB_PORT_IMS_LIVE', '23306'),
            'database' => env('DB_DATABASE_IMS_LIVE', 'ims'),
            'username' => env('DB_USERNAME_IMS_LIVE', 'root'),
            'password' => env('DB_PASSWORD_IMS_LIVE', 'Nwlcmtthjngl!1'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];
