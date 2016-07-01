<?php

    require_once "../config.php";
    require_once "../helper.php";
    
    
    $all = $_GET["all"];
    $numbers = $_GET["numbers"];
    
    if($numbers == "true"){
        $players = $database->select("players",["id"],"WHERE DATE_SUB(CURDATE(),INTERVAL 14 DAY) >= last_update_date");
        foreach ($players as $player) {
            echo "<a>".$player['id']."</a></br>";
            
            
        }
    }
    /*
    else if($all == "true"){
        
        echo "All players in database will upgrade...</br>";
        $players = $database->select("players",["id"],"");
        foreach ($players as $player) {
            $time_start = microtime_float();
            echo insert_update_charakter_by_id($player['id'])."</br>";
            $time_end = microtime_float();
            $time = $time_end - $time_start;
            echo "In $time Sekunden.</br></br>";
            
        }
    }*/
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