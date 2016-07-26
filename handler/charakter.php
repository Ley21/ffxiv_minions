
<?php

    require_once "../config.php";
    require_once "../helper.php";
    
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
    $player_entry = $player[0];
    $p_id = $player_entry["id"];
    $p_name = ucwords($player_entry["name"]);
    $p_world = $player_entry["world"];
    $p_server = ucwords($player_entry["world"]);
    $p_title = get_title_language_text($player_entry["title"]);
    $p_race = $player_entry["race"];
    $p_gc = get_language_text("gc_names")[$player_entry["grandCompany"]];
    $p_fc = $player_entry["freeCompany"];
    $p_fc_id = $player_entry["freeCompanyId"];
    $p_portrait = $player_entry["portrait"];
    
    //Get existing minions
    $exitsts_minions = $database->select("minions", 
        ["[>]player_minion" => ["id" => "m_id"]],"*",
        ["player_minion.p_id[=]"=>$p_id]);
        
    //Get missing minions
    $missing_minions = $database->select("minions", "*",
        "WHERE id NOT IN (SELECT id FROM minions LEFT JOIN player_minion ON minions.id = player_minion.m_id
        WHERE player_minion.p_id=$p_id)");

    //Get existing mounts
    $exitsts_mounts = $database->select("mounts", 
        ["[>]player_mounts" => ["id" => "m_id"]],"*",
        ["player_mounts.p_id[=]"=>$p_id]);
        
    //Get missing mounts
    $missing_mounts = $database->select("mounts", "*",
        "WHERE id NOT IN (SELECT id FROM mounts LEFT JOIN player_mounts ON mounts.id = player_mounts.m_id
        WHERE player_mounts.p_id=$p_id)");

    $lodestone_lang = get_lang() == "ja" ? "jp" : get_lang();
    $charakter_link = "http://$lodestone_lang.finalfantasyxiv.com/lodestone/character/$p_id";
    
    /*    
    $global_rank = $database->query("SELECT p.id,p.name,p.world,(SELECT COUNT(pm.p_id) FROM player_minion as pm WHERE pm.p_id=p.id) as minions,
        (SELECT rank FROM (SELECT id, m,@curRank := @curRank + 1 as rank FROM (SELECT pl.id,(SELECT COUNT(plm.p_id) FROM player_minion as plm WHERE plm.p_id=pl.id) as m  FROM `players` as pl  GROUP BY m  ORDER BY m DESC) as ranking, (SELECT @curRank := 0)  r)  as ranking WHERE m = minions) as rank FROM `players` as p WHERE id=".$p_id)->fetchAll();
    $world_rank = $database->query("SELECT p.id,p.name,p.world, 
        (SELECT COUNT(pm.p_id) FROM player_minion as pm WHERE pm.p_id=p.id) as minions,
        (SELECT rank FROM (SELECT id, m,@curRank := @curRank + 1 as rank FROM (SELECT pl.id,(SELECT COUNT(plm.p_id) FROM player_minion as plm WHERE plm.p_id=pl.id) as m  FROM (SELECT * FROM `players` WHERE world LIKE '$p_world') as pl  GROUP BY m  ORDER BY m DESC) as ranking, (SELECT @curRank := 0)  r)  as ranking WHERE m = minions) as rank FROM (SELECT * FROM `players` WHERE world LIKE '$p_world') as p WHERE id=".$p_id)->fetchAll();
    */
    //Show all minions as tables
    echo "<center>";
    echo '<div class="row"><div class="col-md-4">';
    echo '<div class="panel panel-primary"><div class="panel-heading"><h4><b>'.get_language_text("charakter").'</b></h4></div>';
    echo '<div class="panel-body">';
    echo "<div id='$p_id' class='player_id'></div>";
    echo "<img src=$p_portrait class='img-rounded img-responsive'>";
    echo '</div>';
    echo get_col_row(get_language_text("name"),"<a href='$charakter_link' target='_blank' id='p_name'>$p_name</a>");
    echo get_col_row(get_language_text("world"),$p_server);
    if(!empty($p_title)){
        echo get_col_row(get_language_text("title_char"),$p_title);
    }
    echo get_col_row(get_language_text("race"),$p_race);
    echo get_col_row(get_language_text("grandCompany"),$p_gc);
    echo get_col_row(get_language_text("freeCompany"),"<a id='$p_fc_id' class='freeCompany'>$p_fc</a>");
    //echo get_col_row(get_language_text("gl_rank"),$global_rank[0]["rank"]);
    //echo get_col_row(get_language_text("w_rank"),$world_rank[0]["rank"]);
    echo "<div class='row'><button type='button' class='btn' id='char_button' style='width:83%'></button></div>";
    echo '</br></div></div>';
    echo '<div class="col-md-8">';
    echo create_thumbnail(get_language_text("owned_minions"),$exitsts_minions,"minion");
    echo create_thumbnail(get_language_text("owned_mounts"),$exitsts_mounts,"mount");
    echo '</div></div>';
    echo create_table(get_language_text("missing_minions"),$missing_minions,"minion");
    echo create_table(get_language_text("missing_mounts"),$missing_mounts,"mount");
    echo "</center>";

    
    function get_col_row ($title,$value){
         return "<div class='row'>
                    <div class='col-xs-1 col-sm-1'></div>
                    <div class='col-xs-7 col-sm-5 info_grid'><b>$title</b></div>
                    <div class='col-xs-3 col-sm-5 info_grid_text'>$value</div>
                    <div class='col-xs-1 col-sm-1'></div>
                    </div>";
    }
    
?>