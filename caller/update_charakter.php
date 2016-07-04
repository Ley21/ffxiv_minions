<?php

    require_once "../config.php";
    require_once "../helper.php";
    header("Content-Type: text/html; charset=utf-8");
    
    $all = $_GET["all"];
    $numbers = $_GET["numbers"];
    
    if($numbers == "true"){
        $players = $database->select("players",["id"],"WHERE DATE_SUB(CURDATE(),INTERVAL 14 DAY) >= last_update_date");
        foreach ($players as $player) {
            echo "<a>".$player['id']."</a></br>";
            
            
        }
    }
    else{
        $id = $_GET["id"];
        echo insert_update_charakter_by_id($id);
    }
    
    function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
    
    
?>