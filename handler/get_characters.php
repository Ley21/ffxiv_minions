<?php
    require_once "../config.php";
    require_once "../helper.php";

    $fc = $_GET["fc"];
    if(!empty($fc)){
        $players = $database->select('players',
            "id","WHERE last_update_date < DATE_SUB( NOW( ) , INTERVAL 1 DAY) AND freeCompanyId = $fc");
        $json_informations = json_encode($players);
        echo $json_informations;
        exit;
    }
    
?>