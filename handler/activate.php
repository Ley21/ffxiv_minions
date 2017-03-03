<?php
    $url = 'http' . ($ssl ? 's' : '') . '://' ."{$_SERVER['HTTP_HOST']}";
    header("Refresh:10; url=$url");
    require_once "../config.php";
    require_once "../helper.php";
    
    validate_activation_link($_GET['id'],$_GET['key']);

?>