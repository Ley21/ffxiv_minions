
<?php

    require_once "../config.php";
    require_once "../helper.php";
    
    $id = $_GET["id"];
    $show = $_GET["show"] == "false" ? false : true;
    $logs = "";
    $player;
    $update = $_GET["update_mode"] == "true" ? true : false;
    if($update){
        $player_id = $database->get('players',
            "id","WHERE last_update_date < DATE_SUB( NOW( ) , INTERVAL 14 DAY)");
        if($player_id == null){
            echo "Finish.";
            exit;
        }
        $id = $player_id;
        echo "Updated player id: $id";
    }
    if(!empty($id)){
        
        //Get player from database if exists.
        $player = $database->select('players',
            "*", ["id[=]"=> $id]);//Check if the last update date is longer then one day ago.
        if($player[0]['last_update_date'] != date("Y-m-d")){
            $logs .= insert_update_charakter_by_id($id);
            
                
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
            $output = insert_update_charakter_by_name($name,$server);
            if($output == null){
                echo "<p>Could not find character $name from $server.<p>";
                exit;
            }
                
        }
    }
    
    if(empty($player)){
        
        //Get player id
        $player = $database->select('players',
            "*", ["name[=]"=> $name]);
    }
    
    if($show){
        $player_entry = $player[0];

        $rank = array('global'=>create_char_ranking($player_entry["id"]),
            'world'=>create_char_ranking($player_entry["id"],$player_entry["world"]));
        $title = array('character'=>get_language_text("charakter"),
            'name'=>get_language_text("name"),
            'world'=>get_language_text("world"),
            'race'=>get_language_text("race"),
            'title'=>get_language_text("title_char"),
            'gender'=>get_language_text("gender"),
            'gc'=>get_language_text("grandCompany"),
            'fc'=>get_language_text("freeCompany"),
            'rank'=>array(
                'all'=>get_language_text("all"),
                'minions'=>get_language_text("minions"),
                'mounts'=>get_language_text("mounts"),
                'global'=>get_language_text("gl_rank"),
                'world'=>get_language_text("w_rank")),
            'owned'=>array(
                'minions'=>get_language_text("owned_minions"),
                'mounts'=>get_language_text("owned_mounts")),
            'rarest'=>get_language_text('rarest'),
            'sync' => get_language_text('last_synced'));
                
            
        $player_data = array('id'=>$player_entry["id"],
            'name'=>ucwords($player_entry["name"]),
            'img'=>$player_entry["portrait"],
            'lodestone'=>get_lodestone_link($player_entry["id"]),
            'world'=>ucwords($player_entry["world"]),
            'race'=>$player_entry["race"],
            'title'=>empty($player_entry["title"]) ? "" : get_title_language_text($player_entry["title"]),
            'gc'=>get_language_text("gc_names")[$player_entry["grandCompany"]],
            'fc'=>array(
                'id'=>$player_entry["freeCompanyId"],
                'name'=>$player_entry["freeCompany"]),
            'gender'=>get_language_text($player_entry["gender"]),
            'rank'=>$rank,
            'sync'=>$player_entry['last_update_date']);
        
        $player_data["minions_count"] = get_count("player_minion",$player_data['id']);
        $player_data["mounts_count"] = get_count("player_mounts",$player_data['id']);
        
        
        $player_data["minions"] = get_player_collectables("minions","player_minion",$player_data['id']);
        $player_data["mounts"] = get_player_collectables("mounts","player_mounts",$player_data['id']);
        $player_data["rarest"] = get_rarest_object($player_data['id']);
        
        $missing_minions = create_table(get_language_text("missing_minions"),
            "minion", false,"","WHERE minions.id NOT IN (SELECT minions.id FROM minions 
                LEFT JOIN player_minion ON minions.id = player_minion.m_id 
                WHERE player_minion.p_id=".$player_data['id'].")");
        $missing_mounts = create_table(get_language_text("missing_mounts"),
            "mount", false,"","WHERE mounts.id NOT IN (SELECT mounts.id FROM mounts 
                LEFT JOIN player_mounts ON mounts.id = player_mounts.m_id 
                WHERE player_mounts.p_id=".$player_data['id'].")");
            
        
        $smarty = new Smarty();
        $smarty->assign('player', $player_data);
        $smarty->assign('title', $title);
        $smarty->assign('missing_minions_table',$missing_minions);
        $smarty->assign('missing_mounts_table',$missing_mounts);
        $smarty->display('../template/character.tpl');
    }
    else{
        echo "Character would not be shown.";
        echo $logs;
        exit;
    }
    
?>