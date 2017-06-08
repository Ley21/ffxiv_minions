<?php

    // If you installed via composer, just use this code to requrie autoloader on the top of your projects.
    require 'vendor/autoload.php';
    
    $mail_config = [
            "smtpserver" => 'smtp.udag.de',
            "mailaddress" =>"support@ffxivcollector.com",
            "username" => 'ffxivcollector-com-0001',
            "password" => 'suQPUpvH25o9Y2KUv8Fx',
            "port" => 25,
            "secure" => 'tls'
        ];
    $activate_mail = false;
    
    $ssl = false;
    
    // Initialize
    $database = new medoo([
        'database_type' => 'mysql',
        'database_name' => 'minions',
        'server' => '127.0.0.1',
        'username' => 'minions',
        'password' => 'S3EWrcrcYPhzEqpN',
        'charset' => 'utf8'
    ]);
    
    $external_key = "Zva6kX9iH83s5iA0u35127gkVkka3Hci";
    

?>
