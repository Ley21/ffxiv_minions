<?php
    require_once "../config.php";
    require_once "../helper.php";
    
    $type = $_GET["type"];
    echo create_ranking($type);

?>