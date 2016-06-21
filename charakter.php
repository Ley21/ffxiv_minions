
<?php

    require_once "config.php";
    require_once "helper.php";
    
    $id = $_GET["id"];
    $player;
    if(!empty($id)){
        
        //Get player from database if exists.
        $player = $database->select('players',
            "*", ["id[=]"=> $id]);//Check if the last update date is longer then one day ago.
        if($player[0]['last_update_date'] != date("Y-m-d")){
            insert_update_charakter_by_id($id);
                
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
            insert_update_charakter_by_name($name,$server);
                
        }
    }
    
    
    if(empty($player)){
        
        //Get player id
        $player = $database->select('players',
            "*", ["name[=]"=> $name]);
    }
    $p_id = $player[0]["id"];
    $p_name = ucwords($player[0]["name"]);
    $p_server = ucwords($player[0]["world"]);
    $p_portrait = ucwords($player[0]["portrait"]);
    
    //Get existing minions
    $exitsts_minions = $database->select("minions", 
        ["[>]player_minion" => ["id" => "m_id"]],"*",
        ["player_minion.p_id[=]"=>$p_id]);
        
    //Get missing minions
    $missing_minions = $database->select("minions", "*",
        "WHERE id NOT IN (SELECT id FROM minions LEFT JOIN player_minion ON minions.id = player_minion.m_id
        WHERE player_minion.p_id=$p_id)");



    //Show all minions as tables
    echo "<center>";
    echo '<div class="row"><div class="col-md-3">';
    echo '<div class="page-header">'."<h1>$p_name</br><small>$p_server</small></h1>";
    echo "<img src=$p_portrait class='img-rounded img-responsive'></div>";
    echo '</div>';
    echo '<div class="col-md-9">';
    echo create_thumbnail("Owned Minions",$exitsts_minions);
    echo '</div></div>';
    echo create_table("Missing Minions",$missing_minions);
    echo "</center>";

    
    
    
?>