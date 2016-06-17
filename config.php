<?php
    header("Content-Type: text/html; charset=utf-8");
    
    // If you installed via composer, just use this code to requrie autoloader on the top of your projects.
    require 'vendor/autoload.php';
    require 'vendor/viion/xivpads-lodestoneapi/api-autoloader.php';
    
    // Initialize
    $database = new medoo([
        'database_type' => 'mysql',
        'database_name' => 'minions',
        'server' => getenv('IP'),
        'username' => 'minions',
        'password' => 'xmdRxXLaddKmcWd5',
        'charset' => 'utf8'
    ]);

?>