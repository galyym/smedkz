<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;

class DatabaseService
{
    public function connectDB(string $dbName)
    {
        Config::set('database.connections.'.$dbName, [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env($dbName.'_DB_HOST'),
            'port' => env($dbName.'_DB_PORT'),
            'database' => env($dbName.'_DB_NAME'),
            'username' => env($dbName.'_DB_USER'),
            'password' => "",
            'unix_socket' => "",
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null
        ]);
    }

}
