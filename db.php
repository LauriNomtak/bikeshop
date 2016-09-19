<?php
/**
 * Created by PhpStorm.
 * User: Lauri
 * Date: 19.09.2016
 * Time: 14:20
 */

use Doctrine\DBAL\DriverManager;


function createConnection() {
    $config = new \Doctrine\DBAL\Configuration();

    $connectionParams = array(
        'dbname' => 'bikeshop',
        'user' => 'postgres',
        'password' => 'postgres',
        'host' => 'localhost',
        'port' => '5432',
        'driver' => 'pdo_pgsql',
    );

    return DriverManager::getConnection($connectionParams, $config);
}


