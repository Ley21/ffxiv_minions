<?php
    require_once "../config.php";
    require_once "../helper.php";

    $minion_id = $_GET["minion"];
    $players = $database->select("player_minion", "p_id",["m_id"=>$minion_id]);
    $json_informations = json_encode($players);
    echo $json_informations;
?>
