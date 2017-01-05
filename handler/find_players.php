<?php
    require_once "../config.php";
    require_once "../helper.php";

    $minion_id = $_GET["minion"];
    if(!empty($minion_id)){
        $players = $database->select("player_minion", "p_id",["m_id"=>$minion_id]);
        $json_informations = json_encode($players);
        echo $json_informations;
        exit;
    }
    $mount_id = $_GET["mount"];
    if(!empty($mount_id)){
        $players = $database->select("player_mounts", "p_id",["m_id"=>$mount_id]);
        $json_informations = json_encode($players);
        echo $json_informations;
        exit;
    }
    
?>
