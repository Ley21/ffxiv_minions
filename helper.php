<?php
    require_once "g_helper.php";
    require_once "config.php";
    require_once "language.php";
    
    $random_id = 0;
    
    
    function get_language_text($name,$lang=""){
        global $language_texts;
        
        $languageObject = $language_texts[$name][empty($lang) ? get_lang():$lang];
        return empty($languageObject) ? $language_texts[$name]["en"] : $languageObject;
    }
    
    function get_language_method($method){
        $methodes_en = get_language_text("methodes","en");
        $index = array_search($method,$methodes_en);
        return get_language_text("methodes")[$index];
    }
    
    function get_title_language_text($title){
        
        if(get_lang() != "en"){
            $getdata = http_build_query(
                array('one'=>'titles','string'=>$title));
            $context = stream_context_create($opts);
            $search_url = "https://api.xivdb.com/search?".$getdata;
            $json = file_get_contents($search_url);
            $obj = json_decode($json);
            $prop = "name_".get_lang();
            $translated_title = $obj->titles->results[0]->$prop;
            return $translated_title;
        }
        return $title;
    }
    
    function get_list_of($type){
        global $database;
        
        $objects = $database->select($type,["name", "name_".get_lang()],["ORDER"=>"name_".get_lang()]);
        return $objects;
        
    }
    
    function get_latest_patch(){
        global $database;
        $patches = $database->query("SELECT DISTINCT patch FROM minions")->fetchAll();
        $floatPatches = array();
        
        foreach($patches as $patch) {
        	$version = $patch['patch'];
        	$float = floatval($version);
        	array_push($floatPatches, $float);
        }
        
        sort($floatPatches);
        $lastPatch = number_format(end($floatPatches) , 2, ".", "");
        $lastPatch = (float)$lastPatch;
        return $lastPatch;
    }
    
    function create_table($title,$type,$latestPatch=false,$methodName="",$condition = ""){
        global $database;
        
        if(empty($condition)){
            $condition = empty($methodName) || $methodName == "All" ? "" : ["method[=]"=>$methodName];
            $condition = $latestPatch ? ["patch[=]" => get_latest_patch()] : $condition;
        }
        
        $sql_data = $database->select($type."s",
            ["[>]".$type."s_method"=>["id"=>"m_id"]],
            "*", $condition);
        
        $smarty = new Smarty();
        $smarty->assign('tableTitle', $title);
        $smarty->assign('tableCount', count(unique_multidim_array($sql_data,"id")));
        $smarty->assign('tableHeadIconTitle', get_language_text("icon"));
        $smarty->assign('tableHeadNameTitle', get_language_text("name"));
        $smarty->assign('tableHeadPatchTitle', get_language_text("patch"));
        $smarty->assign('tableHeadCanFlyTitle', $type == "moun" ? get_language_text("can_fly"):"");
        $smarty->assign('tableHeadMethodTitle', get_language_text("method"));
        $smarty->assign('tableHeadDescriptionTitle', get_language_text("description"));
        $smarty->assign('tableId', "table_".$type);
        
        
        $lang = get_lang();
        $objects = array();
        
        foreach($sql_data as $minion_data){
            //Workaround for Red Barron id=103
            if($type == "mount" && $minion_data['id'] == "103"){
                continue;
            }
            $m_id = $minion_data['id'];
            $dom_id = $type."_".$m_id;
            $name = ucwords($minion_data['name_'.$lang]);
            $icon_url = $minion_data['icon_url'];
            $patch = $minion_data['patch'];
            $can_fly = !empty($minion_data['can_fly']) ? 
                ($minion_data['can_fly'] == 0 ? get_language_text("no") : get_language_text("yes")) 
                : get_language_text("unknown");
            
            $base_url = get_lang() == "en" ? "https://xivdb.com" : "https://$lang.xivdb.com";
            $url = "$base_url/$type/$m_id";
            
            $condition = empty($methodName) || $methodName == "All" ? ["m_id[=]"=>$m_id]: ["AND"=>["m_id[=]"=>$m_id,"method[=]"=>$methodName]];
            $obj_methodes = $database->select($type == "minion" ? "minions_method" : "mounts_method",
                "*",$condition);
            
            
            if(empty($obj_methodes)){
                $objects[] = array('id'=>$dom_id,'class'=>"",'url'=>$url,
                    'icon'=>$icon_url,'name'=>$name,'patch'=>$patch,
                    'canFly'=>$can_fly,'method'=>get_language_text("unknown"),
                    'methodDesc'=>"");
            }
            else{
                foreach($obj_methodes as $method){
                    $methodes_en = get_language_text("methodes","en");
                    $m_index = array_search($method['method'],$methodes_en);
                    $method_name = get_language_text("methodes")[$m_index];
                    $method_name .= $method['available'] ? "" : "(N\A)";
                    $methodDesc = $method['method_description_'.$lang];
                    $methodDesc = empty($methodDesc) ? $method['method_description_en'] : $methodDesc;
                    $class = $method['available'] ? "" : "active text-muted";
                    
                    $objects[] = array('id'=>$dom_id,'class'=>$class,'url'=>$url,
                        'icon'=>$icon_url,'name'=>$name,'patch'=>$patch,
                        'canFly'=>$can_fly,'method'=>$method_name,
                        'methodDesc'=>$methodDesc);
                }
            }
            
        }
        $objects = array_map("unserialize", array_unique(array_map("serialize", $objects)));
        $smarty->assign('objects', $objects);
        return $smarty->fetch($_SERVER['DOCUMENT_ROOT'].'/template/table.tpl');
    }
    
    function create_object_ranking($type = ""){
        global $database;
        
        $smarty = new Smarty();
        $smarty->assign('tableHeaderNr', get_language_text("nr"));
        $smarty->assign('tableHeaderName', get_language_text("name"));
        $smarty->assign('tableHeaderPercent', get_language_text("percent"));
        $smarty->assign('tableHeaderCount', get_language_text("count"));
        
        $type = explode("_", $type)[1];
        
        $count_players = $database->count("players");
        
        $base_url = get_lang() == "en" ? "https://xivdb.com" : "https://".get_lang().".xivdb.com";
            
        
        switch($type){
            case "all":
                $minions = get_rarity_objects("minions","player_minion");
                $mounts = get_rarity_objects("mounts","player_mounts");
                $result = array_merge($minions,$mounts);
                
                
                function custom_sort($a,$b) {
                    return $a['number']>$b['number'];
                }
                
                usort($result, "custom_sort");
                break;
            case "minions":
                $result = get_rarity_objects($type,"player_minion");
                break;
            case "mounts":
                $result = get_rarity_objects($type,"player_mounts");
                break;
        }
        $count_befor = $result[0]['number'];
        $nr = 1;
        $objects = array_map(function($obj) use (&$count_befor,&$count_players,&$nr){
                if($count_befor != $obj['number']){
                    $nr++;
                }
                return array(
                    "nr"=>$nr,
                    "id" => $obj['id'],
                    "name"=>$obj['name_'.get_lang()],
                    "icon"=> $obj['icon_url'],
                    "type" => rtrim($obj["type"], "s"),
                    "link" => $base_url."/".$obj["type"]."/".$obj['id'],
                    "percent" => ($obj['number']  == 0 ? 0 : round(( $obj['number'] / $count_players * 100 ),2))." %",
                    "count" => $obj['number']);
            }, $result);
        $smarty->assign('objects', $objects);
        return $smarty->fetch($_SERVER['DOCUMENT_ROOT'].'/template/ranking_rarity.tpl');
    }
    
    function get_rarity_objects($table, $player_table){
        global $database;
        $result = $database->query("SELECT COUNT( ".$player_table.".p_id ) AS number, ".$table.".* 
            FROM ".$player_table." RIGHT JOIN $table ON $player_table.m_id = ".$table.".id 
            GROUP BY ".$table.".id ORDER BY number ASC ")->fetchAll();
        $result = array_map(function($obj) use (&$table){
                $obj["type"] = $table == "mounts" ? "mount" : "minion";
                return $obj;
            }, $result);
        return $result;
    }
    
    function create_ranking($type = "",$fc = ""){
        global $database;
        
        $smarty = new Smarty();
        $smarty->assign('tableHeaderNr', get_language_text("nr"));
        $smarty->assign('tableHeaderName', get_language_text("name"));
        $smarty->assign('tableHeaderWorld', get_language_text("world"));
        $smarty->assign('tableHeaderCountMinions', get_language_text("minions"));
        $smarty->assign('tableHeaderCountMounts', get_language_text("mounts"));
        $smarty->assign('tableHeaderCountAll', get_language_text("all"));
        $smarty->assign('tableHeaderLastSync', get_language_text("last_synced"));
        $smarty->assign('type',$type);
        $smarty->assign('syncBtnText',get_language_text("update_char"));
        
        $ranking = get_ranking_players($type,$fc);
        
        
        
        if(!empty($fc)){
            $smarty->assign('search',true);
            $minions = $database->select("minions",["id","name_".get_lang()],"ORDER BY "."name_".get_lang());
            $mounts = $database->select("mounts",["id","name_".get_lang()],"ORDER BY "."name_".get_lang());
            
            $smarty->assign('search_minion',get_language_text("has_minion"));
            $smarty->assign('search_mount',get_language_text("has_mount"));
            $smarty->assign('not_minion',get_language_text("not_minion"));
            $smarty->assign('not_mount',get_language_text("not_mount"));
            $smarty->assign('update_all',get_language_text("update_all"));
            
            $minions = array_map(function($obj){
                return array("id"=>$obj['id'],"name"=>$obj["name_".get_lang()]);
            },$minions);
            $mounts = array_map(function($obj){
                return array("id"=>$obj['id'],"name"=>$obj["name_".get_lang()]);
            },$mounts);
            
            
            $smarty->assign('minions',$minions);
            $smarty->assign('mounts',$mounts);
            $missing_ranking_players = get_missing_player_ranking_rows($fc);
            foreach($missing_ranking_players as $player){
                array_push($ranking,$player);    
            }
        }
        
        
        
        $smarty->assign('players', $ranking);
        return $smarty->fetch($_SERVER['DOCUMENT_ROOT'].'/template/ranking.tpl');
    }
    
    function get_ranking_players($type = "",$fc="",$world=""){
        global $database;
        $condition = empty($fc) ? "" : ["freeCompanyId"=>$fc];
        if(!empty($world)){
            $condition = empty($condition) ? ['world'=>$world] : ['AND'=>[$condition,'world'=>$world]];
        }
        $players = $database->select("players",["id","name","world","last_update_date"],
            $condition);
            
        $ranking = array();
        foreach($players as $player){
            $count_minions = $database->count("player_minion",["p_id[=]"=>$player["id"]]);
            $count_mounts = $database->count("player_mounts",["p_id[=]"=>$player["id"]]);
            $count = $count_minions+ $count_mounts;
            $key;
            switch($type){
                case "minions":
                    $key = $count_minions;
                    break;
                case "mounts":
                    $key = $count_mounts;
                    break;
                default:
                    $key = $count;
                    break;
                    
            }
            $ranking[] = array($key,(object)array("all"=>$count,"minions"=>$count_minions,"mounts"=>$count_mounts,"player"=>$player));
            
        }
        arsort($ranking);
        
        $ordered_ranking = array();
        $befor = 0;
        $nr = 1;
        foreach($ranking as $rank_arr){
            $rank = $rank_arr[1];
            $date_diff = date_diff(date_create($rank->player['last_update_date']), date_create(date("Y-m-d")));
            $old = $date_diff->days >= 14;
            
            $ordered_ranking[] = array("nr"=>$nr,"id"=>$rank->player['id'],
                "name"=>ucwords($rank->player['name']),"world"=>ucfirst($rank->player['world']),
                "minions"=>$rank->minions,"mounts"=>$rank->mounts,"all"=>$rank->all,
                "sync"=>$rank->player['last_update_date'],"old"=>$old);
            if($befor != $rank_arr[0]){
                $befor = $rank_arr[0];
                $nr++;
            }
        }
        return $ordered_ranking;
    }
    
    function get_missing_player_ranking_rows($fc){
        global $database;
        
        $api = new \Lodestone\Api;
        $free_company_members = (Object)$api->getFreeCompanyMembers($fc);
        
        $rows = array();
        foreach($free_company_members->members as $member){
            $in_table = $database->has("players", ["AND"=>["freeCompanyId"=>$fc,"id"=>$member["id"]]]);
            if(!$in_table){
                $rows[] = array("nr"=>"999","id"=>$member["id"],
                    "name"=>ucwords($member["name"]),"world"=>ucfirst($member["world"]),
                    "minions"=>"000","mounts"=>"000","all"=>"000",
                    "sync"=>"","old"=>true);
            }
        }
        return $rows;
    }
    
    function get_ranking_of_player($id,$world = "",$type="all"){
        global $database;
        
        $ranking = get_ranking_players($type == "all"? "":$type,"",$world);
        
        $nr = 1;
        $count_befor = 0;
        foreach($ranking as $rank){
            if($rank['id'] == $id){
                return $nr;
            }
            $count = $rank[$type];
            if($count_befor != $count){
                $nr++;
                $count_befor = $count;
            }
            
            
        } 
    }
    
    function create_char_ranking($id,$world = ""){
        $gl_rank_all = get_ranking_of_player($id,$world);
        $gl_rank_minion = get_ranking_of_player($id,$world,"minions");
        $gl_rank_mounts = get_ranking_of_player($id,$world,"mounts");
        $rank = array('all'=>$gl_rank_all,'minions'=>$gl_rank_minion,'mounts'=>$gl_rank_mounts);
        
        return $rank;
    }
    
    function get_player_collectables($table,$con_table,$player_id){
        global $database;
        $obj_arr = array();
        $elements = $database->select($table, 
            ["[>]".$con_table => ["id" => "m_id"]],"*",
            ["$con_table.p_id[=]"=>$player_id]);
        $lang = get_lang() == "en" ? "":get_lang().".";
        $type = $table == "minions" ? "minion" : "mount";
        foreach($elements as $elem){
            //Workaround for Red Barron id=103
            if($table == "mounts" && $elem['id'] == "103"){
                continue;
            }
            $obj_arr[] = array(
                'id'=>$elem['id'],
                'name'=>$elem['name_'.get_lang()],
                'xivdb'=>"https://".$lang."xivdb.com/$type/".$elem['id'],
                'icon'=>$elem['icon_url']);
        }  
        
        return $obj_arr;
    }
    
    function get_count($table,$id){
        global $database;
        return $database->count($table,["p_id[=]"=>$id]);
    }
    
    function get_rarest_object($id){
        global $database;
        
        $rarest = array();
        $lang = get_lang() == "en" ? "":get_lang().".";
        foreach(array("minion","mount") as $type){
            $table = $type == "mount" ? "player_".$type."s" : "player_".$type; 
            $result = $database->query("SELECT COUNT( p_id ),m_id FROM $table GROUP BY m_id ORDER BY COUNT( p_id ) ASC")->fetchAll();
            foreach($result as $obj){
                if($database->has($table,["AND"=>["p_id"=>$id,"m_id"=>$obj['m_id']]])){
                    $elem = $database->get($type."s","*",["id[=]"=>$obj['m_id']]);
                    $rarest[$type] = array(
                        'id'=>$elem['id'],
                        'name'=>$elem['name_'.get_lang()],
                        'xivdb'=>"https://".$lang."xivdb.com/$type/".$elem['id'],
                        'icon'=>$elem['icon_url']);
                        break;
                }
            }
        }
        
        return $rarest;
    }
    
    function get_method_lang($method_en){
        $methodes_en = get_language_text("methodes","en");
        $index = array_search($method_en,$methodes_en);
        return get_language_text("methodes")[$index];
    }
    
    function get_methodes($table){
        global $database;
        
        $methodes_db = $database->query("SELECT DISTINCT method FROM ".$table."_method")->fetchAll();
        $methodes_db = array_map(function($elm){
            return $elm['method'];
        },$methodes_db);
        $methodes = get_language_text("methodes");
        $methodes_en = get_language_text("methodes","en");
        $methodes_array = array();
        foreach($methodes as $i=>$methode){
            $mehtod_en = $methodes_en[$i];
            $methode_get = urlencode ($mehtod_en);
            if(in_array($mehtod_en,$methodes_db) || $mehtod_en == "All"){
                $methodes_array[] = array('name' => $methode, 'id' => $methode_get);
            }
        }
        
        return $methodes_array;
    }
    
    function insert_update_charakter_by_id($id){
        //Get charakter from lodestone
        $api = new \Lodestone\Api;
        $character = (Object)$api->getCharacter($id);
        
        if(empty($character->getid()) && !empty($id)){
            echo "Character with id '$id' was deleted.";
            delete_char($id);
        }
        else{
        //$character = Lodestone::findCharacterById($id);
        return insert_update_charakter($character);
        }
    }
    
    function insert_update_charakter_by_name($name,$server){
        //Get charakter from lodestone
        $api = new \Lodestone\Api;

        $server = ucwords($server);
        $name = ucwords($name);
        // search for characters
        $characters = $api->searchCharacter($name, $server);
        
        // loop through characters
        foreach($characters['results'] as $char) {
            $char = (Object)$char;
            if($char->getserver() == $server && 
                $char->getname() == $name){
                return insert_update_charakter_by_id($char->getid());
            }
        }
        
    }
    
    function get_freeCompany($fc_id){
        if($fc_id == null){
            return null;
        }
        $api = new \Lodestone\Api;
        $fc = (Object) $api->getFreeCompany($fc_id);
        return $fc;
    }
    
    function insert_update_charakter($character){
        global $database;
        if($character == false){
            return null;
        }
        
        if(empty($character->getid())){
            return "Could not find the charakter '$name' on server '$server'";
            exit;
        }
        
        $c_name = strtolower($character->getname());
        $c_world = strtolower($character->getserver());
        $c_portrait = $database->quote($character->getportrait());
        
        $fc = get_freeCompany($character->getfreecompany());
        $gc = (Object) $character->getgrandcompany();
        $guardian = (Object)$character->getguardian();
        
        
        //Check if an charakter with the same id already exists
        $p_id = $database->get("players", "id", ["id" => $character->getid()]);
        $output;
        
        $data = [
            	"name" => $c_name,
            	"world" => $c_world,
            	"title" => $character->gettitle(),
            	"portrait" => $c_portrait,
            	"race" => $character->getrace(),
            	"clan" => $character->getclan(),
            	"gender" => strtolower($character->getgender()),
            	"nameday" => $character->getnameday(),
            	"guardian" => $guardian->getname(),
            	"grandCompany" => $gc->getname(),
            	"freeCompany" => $fc->getname(),
            	"freeCompanyId" => $character->getfreecompany(),
            	"last_update_date" => date("Y-m-d")
            ];
        $id = $character->getid();
        if(!$player && empty($p_id)){
            $data["id"]= $id;
            //Insert new charakter
            $database->insert("players", $data);
            $output = "New charakter '$c_name' with id '$id' from server '$c_world' was added to database.";
        }
        else{
            //Update existing charakter
            $database->update("players", $data, ["id[=]"=>$id]);
            $output = "Charakter '$c_name' with id '$id' from server '$c_world' was updated.";
        }
        
        
        $collectables = $character->getcollectables();
        //Get all minions from current charakter
        $minions = $collectables->getminions();
        insert_item_char($id,$minions,"minions","player_minion");
        
        $mounts = $collectables->getmounts();
        insert_item_char($id,$mounts,"mounts","player_mounts");
        
        return $output;
    }
    
    function insert_item_char($p_id,$items,$table,$link_table){
        global $database;
        $datas = $database->select($table, [
        	"id",
        	"name"
        ]); 
        
        $new_data = array();
        foreach($datas as $data)
        {
            $new_data[$data['id']] = strtolower($data['name']);
        }
        
        foreach($items as $item){
            
            $player_item = strtolower($item->getname());
            $m_id = array_search($player_item,$new_data);
            if ($m_id !== false) {
                //echo "DB: $new_data[$m_id] - Player: $player_item";
                $database->query("REPLACE INTO $link_table VALUES (
                    $p_id, 
                    $m_id);");
            } else {
                //echo "DB: $new_data[$m_id] - Player: $player_item</br>";
            }
        }
        
    }

    function delete_char($id){
        global $database;
        $database->delete("player_minion", ["p_id[=]" => $id]);
        $database->delete("player_mounts", ["p_id[=]" => $id]);
        $database->delete("players", ["id[=]" => $id]);
    }
    function get_last_id($type){
        $json = file_get_contents("https://api.xivdb.com/search?one=$type");
        $obj = json_decode($json);
        return $obj->$type->results[0]->id;
    }
    
    
    function get_lodestone_link($id){
        $lodestone_lang = get_lang() == "ja" ? "jp" : get_lang();
        $lodestone_lang = $lodestone_lang == "en" ? "eu" : $lodestone_lang;
        $charakter_link = "http://$lodestone_lang.finalfantasyxiv.com/lodestone/character/$id";
        return $charakter_link;
    }
    
    function insert_update_verminion($id,$json_data){
        global $database;
        
        $db_id = $database->get("verminion",["id"],["id[=]"=>$id]);
            
        $data = ["id"=>$json_data->id,
                "race"=>$json_data->race,
                "cost" => $json_data->cost,
                "hp" => $json_data->hp,
                "attack" => $json_data->attack,
                "defense"=>$json_data->defense,
                "speed"=>$json_data->speed,
                "skill_type"=>$json_data->minion_skill_type,
                "skill_cost"=>$json_data->skill_cost,
                "action_en" => $json_data->action_en,
                "action_fr" => $json_data->action_fr,
                "action_de" => $json_data->action_de,
                "action_ja" => $json_data->action_ja,
                "help_en" => $json_data->help_en,
                "help_fr" => $json_data->help_fr,
                "help_de" => $json_data->help_de,
                "help_ja" => $json_data->help_ja,
                "strength_arcana" => $json_data->strength_arcana,
                "strength_eye" => $json_data->strength_eye,
                "strength_gate" => $json_data->strength_gate,
                "strength_shield" => $json_data->strength_shield,
                "has_area_attack" => $json_data->has_area_attack];
            
            if(empty($db_id)){
                $database->insert("verminion",$data);
            }
            else{
                $database->update("verminion",$data,
                    ["id[=]"=>$id]);
            }
    }
    
    function insert_update_minion($id){
        global $database;
        $json = file_get_contents("https://api.xivdb.com/minion/$id");
        $obj = json_decode($json);
        $db_minion = strtolower($obj->name);
        $patch = empty($obj->patch) ? "2.0" : $obj->patch->number;
        
        if(empty($obj->id)){
            //echo "Minion with number '$number' does not exists.";
        }
        elseif($db_minion == "wind-up merlwyb" ||
            $db_minion == "wind-up kan-e" ||
            $db_minion == "wind-up raubahn"){
        }
        elseif($obj->id == 68 ||$obj->id == 69 || $obj->id == 70){
        }
        else{
            $xivdb_icon = $database->quote($obj->icon2);
            if(endsWith($xivdb_icon,"noicon.png'")){
                return;
            }
            $db_id = $database->get("minions",["id"],["id[=]"=>$id]);
            
            $data = ["id"=>$obj->id,
                    "name"=>$obj->name,
                    "icon_url" => $xivdb_icon,
                    "picture_url" => $database->quote($obj->icon),
                    "patch" => $patch,
                    "name_en"=>$obj->name_en,
                    "name_fr"=>$obj->name_fr,
                    "name_de"=>$obj->name_de,
                    "name_ja"=>$obj->name_ja,
                    "description_en" => $obj->info1_en,
                    "description_fr" => $obj->info1_fr,
                    "description_de" => $obj->info1_de,
                    "description_ja" => $obj->info1_ja,
                    "summon_en" => $obj->summon_en,
                    "summon_fr" => $obj->summon_fr,
                    "summon_de" => $obj->summon_de,
                    "summon_ja" => $obj->summon_ja,
                    "behavior" => $obj->behavior];
            
            if(empty($db_id)){
                $database->insert("minions",$data);
            }
            else{
                $database->update("minions",$data,
                    ["id[=]"=>$id]);
            }
            insert_update_verminion($id,$obj);
        }
    }
    
    function insert_update_mount($id){
        global $database;
        $json = file_get_contents("https://api.xivdb.com/mount/$id");
        $obj = json_decode($json);
        $db_minion = strtolower($obj->name);
        $patch = empty($obj->patch) ? "2.0" : $obj->patch->number;
        
        if(empty($obj->id)){
            //echo "Minion with number '$number' does not exists.";
        }
        else{
            $xivdb_icon = $database->quote($obj->icon2);
            if(endsWith($xivdb_icon,"noicon.png")){
                return;
            }
            $db_id = $database->get("mounts",["id"],["id[=]"=>$id]);
            $data = ["id"=>$obj->id,
                    "name"=>$obj->name,
                    "icon_url" => $xivdb_icon,
                    "picture_url" => $database->quote($obj->icon),
                    "patch" => $patch,
                    "name_en"=>$obj->name_en,
                    "name_fr"=>$obj->name_fr,
                    "name_de"=>$obj->name_de,
                    "name_ja"=>$obj->name_ja,
                    "description_en" => $obj->info1_en,
                    "description_fr" => $obj->info1_fr,
                    "description_de" => $obj->info1_de,
                    "description_ja" => $obj->info1_ja,
                    "summon_en" => $obj->summon_en,
                    "summon_fr" => $obj->summon_fr,
                    "summon_de" => $obj->summon_de,
                    "summon_ja" => $obj->summon_ja];
            if(empty($db_id)){
                $database->insert("mounts",$data);
            }
            else{
                $database->update("mounts",$data,
                    ["id[=]"=>$id]);
            }
        }
    }
    
    function create_database(){
        global $database;
        
        $database->query("CREATE TABLE `minions` (
          `id` int(11) NOT NULL,
          `name` varchar(100) NOT NULL,
          `icon_url` varchar(255) NOT NULL,
          `picture_url` varchar(255) NOT NULL,
          `patch` varchar(50) NOT NULL,
          `name_en` varchar(100) NOT NULL,
          `name_fr` varchar(100) NOT NULL,
          `name_de` varchar(100) NOT NULL,
          `name_ja` varchar(100) NOT NULL,
          `description_en` text NOT NULL,
          `description_fr` text NOT NULL,
          `description_de` text NOT NULL,
          `description_ja` text NOT NULL,
          `summon_en` text NOT NULL,
          `summon_fr` text NOT NULL,
          `summon_de` text NOT NULL,
          `summon_ja` text NOT NULL,
          `behavior` varchar(100) NOT NULL,
          `sellable` tinyint(1) NOT NULL DEFAULT '0',
          PRIMARY KEY (`id`)
        );");
        
        $database->query("CREATE TABLE `mounts` (
          `id` int(11) NOT NULL,
          `name` varchar(100) NOT NULL,
          `icon_url` varchar(255) NOT NULL,
          `picture_url` varchar(255) NOT NULL,
          `patch` varchar(50) NOT NULL,
          `can_fly` tinyint(1) NOT NULL DEFAULT '0',
          `name_en` varchar(100) NOT NULL,
          `name_fr` varchar(100) NOT NULL,
          `name_de` varchar(100) NOT NULL,
          `name_ja` varchar(100) NOT NULL,
          `description_en` text NOT NULL,
          `description_fr` text NOT NULL,
          `description_de` text NOT NULL,
          `description_ja` text NOT NULL,
          `summon_en` text NOT NULL,
          `summon_fr` text NOT NULL,
          `summon_de` text NOT NULL,
          `summon_ja` text NOT NULL,
          `sellable` tinyint(1) NOT NULL DEFAULT '0',
          PRIMARY KEY (`id`)
        );");
        $database->query("CREATE TABLE players (
            id INT NOT NULL,
            name VARCHAR(100) NOT NULL,
            world VARCHAR(50) NOT NULL,
            title VARCHAR(100) ,
            portrait VARCHAR(250) NOT NULL,
            race VARCHAR(50) NOT NULL,
            clan VARCHAR(50) NOT NULL,
            gender VARCHAR(50) NOT NULL,
            nameday VARCHAR(200) NOT NULL,
            guardian VARCHAR(100) NOT NULL,
            grandCompany VARCHAR(50) ,
            freeCompany VARCHAR(100),
            freeCompanyId VARCHAR(30),
            last_update_date DATE NOT NULL,
            PRIMARY KEY ( id )
            );");
        $database->query("CREATE TABLE player_minion (
            p_id INT NOT NULL,
            m_id INT NOT NULL,
            primary key (p_id, m_id),
            FOREIGN KEY (p_id) REFERENCES players(id),
            FOREIGN KEY (m_id) REFERENCES minions(id)
            );");
                
        $database->query("CREATE TABLE player_mounts (
            p_id INT NOT NULL,
            m_id INT NOT NULL,
            primary key (p_id, m_id),
            FOREIGN KEY (p_id) REFERENCES players(id),
            FOREIGN KEY (m_id) REFERENCES mounts(id)
            );");
                
        $database->query("CREATE TABLE minions_method (
            m_id INT NOT NULL,
            method VARCHAR(100),
            available tinyint(1),
            method_description_en TEXT,
            method_description_fr TEXT,
            method_description_de TEXT,
            method_description_ja TEXT,
            primary key (m_id, method),
            FOREIGN KEY (m_id) REFERENCES minions(id)
            );");
                
        $database->query("CREATE TABLE mounts_method (
            m_id INT NOT NULL,
            method VARCHAR(100),
            available tinyint(1),
            method_description_en TEXT,
            method_description_fr TEXT,
            method_description_de TEXT,
            method_description_ja TEXT,
            primary key (m_id, method),
            FOREIGN KEY (m_id) REFERENCES mounts(id)
            );");
    }
    
    function create_verminion_table(){
        global $database;
        $tables_exits = $database->query('SELECT 1 FROM verminion LIMIT 1;') != false;
        $database->query("CREATE TABLE IF NOT EXISTS `verminion` (
          `id` int(11) NOT NULL,
          `race` varchar(100) NOT NULL,
          `cost` int(11) NOT NULL,
          `hp` int(11) NOT NULL,
          `attack` int(11) NOT NULL,
          `defense` int(11) NOT NULL,
          `speed` int(11) NOT NULL,
          `skill_cost` int(11) NOT NULL,
          `skill_type` varchar(100) NOT NULL,
          `action_en` varchar(100) NOT NULL,
          `action_fr` varchar(100) NOT NULL,
          `action_de` varchar(100) NOT NULL,
          `action_ja` varchar(100) NOT NULL,
          `strength_arcana` tinyint(1) NOT NULL,
          `strength_eye` tinyint(1) NOT NULL,
          `strength_gate` tinyint(1) NOT NULL,
          `strength_shield` tinyint(1) NOT NULL,
          `has_area_attack` tinyint(1) NOT NULL,
          `help_en` text NOT NULL,
          `help_fr` text NOT NULL,
          `help_de` text NOT NULL,
          `help_ja` text NOT NULL,
          PRIMARY KEY (`id`),
          FOREIGN KEY (id) REFERENCES minions(id)
        );");
    }
    
    function update_table_structur($table,$column,$columnDataType,$afterColumn){
        global $database;
        $result = $database->query("SELECT * FROM information_schema.COLUMNS 
                WHERE TABLE_NAME = '$table' AND COLUMN_NAME = '$column'")->fetchAll();
        if(empty($result)){
            $database->query("ALTER TABLE `$table` ADD `$column` $columnDataType NOT NULL AFTER `$afterColumn`");
        }
    }
    
    function read_write_methode_new($table,$file,$readonly){
        global $database;
        $logs;
        
        $method_table = $table."_method";
        //Read local file
        if(file_exists($file)){
            $json = file_get_contents($file);
            $read_collectables = json_decode($json);
            
            //$missing = 0;
            //Update mehtode from local file
            foreach($read_collectables as $coll){
                $logs .= "-> $table - $coll->id updated.</br>";
                

                if($table == "mounts"){
                    $database->update($table,["can_fly" => $coll->can_fly],["id[=]"=>$coll->id]);
                }
                $database->update($table,["sellable" => $coll->sellable],["id[=]"=>$coll->id]);
                /*if(empty($coll->methodes) 
                    || count($coll->methodes) != $database->count($method_table,["m_id[=]"=>$coll->id])){
                    $database->delete($method_table,["m_id[=]"=>$coll->id]);
                }*/
                foreach($coll->methodes as $j_method){
                    //$logs .= "--> Methode: $j_method->method || Desciption: - $j_method->method_description_en.</br>";
                    $data = ["m_id" => $coll->id,"available"=>$j_method->available,
                        "method" => $j_method->method,
                        "method_description_en" => $j_method->method_description_en,
                        "method_description_fr" => $j_method->method_description_fr,
                        "method_description_de" => $j_method->method_description_de,
                        "method_description_ja" => $j_method->method_description_ja];
                    $method = $database->get($method_table,["method"],["AND"=>["m_id[=]"=>$coll->id,"method[=]"=>$j_method->method]]);
                    if(empty($method)){
                        $database->insert($method_table,$data);
                    }else{
                        $database->update($method_table,$data,["AND"=>["m_id[=]"=>$coll->id,"method[=]"=>$j_method->method]]);
                    }
                }
            }
            $logs .= "===> $table is missing '$missing' methods.</br>";
        }
        else{
            $logs .= "File '$file' does not exists. Could not import methodes.</br>";
        }
        
        if($readonly == "false"){
            $columns = $table == "minions" ?  ["id","name","sellable"] : ["id","name","sellable","can_fly"];
            $objects = $database->select($table,$columns);
            $methodes = array();
            foreach($objects as $obj){
                $obj_methodes = $database->select($method_table,["method","available","method_description_en",
                        "method_description_fr","method_description_de","method_description_ja"],["m_id[=]"=>$obj["id"]]);
                $obj_methodes = $obj_methodes ? $obj_methodes : array(array("method"=>null,"available"=>0,"method_description_en"=>null,
                        "method_description_fr"=>null,"method_description_de"=>null,"method_description_ja"=>null));
                $method = $table == "minions" ? array("id"=>$obj["id"],"name"=>$obj["name"],"sellable"=>$obj["sellable"],"methodes"=>$obj_methodes) 
                            : array("id"=>$obj["id"],"name"=>$obj["name"],"sellable"=>$obj["sellable"],"can_fly" => $obj["can_fly"],"methodes"=>$obj_methodes) ;
                $methodes[] = $method;
            }
            $json_informations = json_encode($methodes,JSON_PRETTY_PRINT);
            file_put_contents($file, $json_informations);  
        }
        
        $logs .= "The methodes for table '$method_table' have been updated.</br></br>";
        return $logs;
    }
    
    function send_mail($subject,$message){
        global $mail_config,$activate_mail;
        $logs ='';
        if($activate_mail){
            $mail = new PHPMailer;
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = $mail_config['smtpserver'];  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $mail_config['username'];                 // SMTP username
            $mail->Password = $mail_config['password'];                           // SMTP password
            $mail->SMTPSecure = $mail_config['secure'];                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = $mail_config['port'];                                    // TCP port to connect to
            $mail->setFrom($mail_config['mailaddress'], 'FFXIV Collector Page');
            $mail->addAddress($mail_config['mailaddress']);
            $mail->isHTML(true);                                  // Set email format to HTML
            
            $mail->Subject = $subject;
            //$mail->Body    = $message;
            $mail->MsgHTML($message);
            
            if(!$mail->send()) {
                $logs .= 'Message could not be sent.';
                $logs .= 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                $logs .= 'Message has been sent';
            }
        }
        else{
            $logs .= "Mail feature is not activated";
        }
        return $logs;
    }
	function unique_multidim_array($array, $key) {
		$temp_array = array();
		$i = 0;
		$key_array = array();
	   
		foreach($array as $val) {
			if (!in_array($val[$key], $key_array)) {
				$key_array[$i] = $val[$key];
				$temp_array[$i] = $val;
			}
			$i++;
		}
		return $temp_array;
	} 
	
	function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }
    
        return (substr($haystack, -$length) === $needle);
    }
    
    function is_mobile(){
    	$useragent=$_SERVER['HTTP_USER_AGENT'];
    	return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4));
    }
    

?>
        
        