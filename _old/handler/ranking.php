<?php
    require_once "../config.php";
    require_once "../helper.php";
    
    $type = $_GET["type"];
    if(strpos($type, "rarity") === false){
        echo create_ranking($type);
    }
    else{
        echo create_object_ranking($type);
    }

?>