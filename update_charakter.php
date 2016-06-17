<?php

    require_once "config.php";
    require_once "helper.php";
    
    $all = $_GET["all"];
    
    if($all == "true"){
        echo "All players in database will upgrade...</br>";
        $players = $database->select("players",["id"],"");
        foreach ($players as $player) {
            echo insert_update_charakter_by_id($player['id'])."</br>";
        }
    }
    else{
        $id = $_GET["id"];
        
        insert_update_charakter_by_id($id);
    }

?>