
<?php

    require_once "../config.php";
    require_once "../helper.php";
    
    $id = $_GET["id"];
    $player;
    if(!empty($id)){
        
        //Get player from database if exists.
        $player = $database->select('players',
            "*", ["id[=]"=> $id]);
        //Check if the last update date is longer then one day ago.
        if($player[0]['last_update_date'] != date("Y-m-d")){
            echo "true";
            exit;
                
        }
            
    }else{
    
        //Get informations from get.
        $name = strtolower($_GET["name"]);
        $server = strtolower($_GET["server"]);
        $esc_name = $database->quote($name);
        //Get player from database if exists.
        $player = $database->select('players',
            "*", ["name[=]"=> $name]);
            //Check if the last update date is longer then one day ago.
        if($player[0]['last_update_date'] != date("Y-m-d")){
            echo "true";
            exit;
                
        }
    }
    
?>