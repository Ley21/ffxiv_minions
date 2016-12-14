<?php
    require_once "config.php";
    require_once "language.php";
    
    $random_id = 0;
    
    function get_lang(){
        $lang = empty($_GET["lang"]) ? $_POST["lang"] : $_GET["lang"];
        $lang = empty($lang) ? "en" : $lang;
        return $lang;
    }
    
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
    
    function create_table($title,$type,$latestPatch=false,$methodName=""){
        global $database;
        
        $condition = empty($methodName) ? "" : ["method[=]"=>$methodName];
        $condition = $latestPatch ? ["patch[=]" => get_latest_patch()] : $condition;
        
        $sql_data = $database->select($type."s",
            ["[>]".$type."s_method"=>["id"=>"m_id"]],
            "*", $condition);
        
        $smarty = new Smarty();
        $smarty->assign('tableTitle', $title);
        $smarty->assign('tableCount', count($sql_data));
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
        $smarty->assign('objects', $objects);
        return $smarty->fetch($_SERVER['DOCUMENT_ROOT'].'/template/table.tpl');
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
            $missing_ranking_players = get_missing_player_ranking_rows($fc);
            //var_dump($missing_ranking_players);
        }
        foreach($missing_ranking_players as $player){
            array_push($ranking,$player);    
        }
        
        
        $smarty->assign('players', $ranking);
        return $smarty->fetch($_SERVER['DOCUMENT_ROOT'].'/template/ranking.tpl');
    }
    
    function get_ranking_players($type = "",$fc=""){
        global $database;
        $players = $database->select("players",["id","name","world","last_update_date"],
            empty($fc) ? "" : ["freeCompanyId"=>$fc]);
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
        
        $api = new Viion\Lodestone\LodestoneAPI();
        $freeCompany = $api->Search->Freecompany($fc,true);
        
        $rows = array();
        foreach($freeCompany->members as $member){
            $in_table = $database->has("players", ["AND"=>["freeCompanyId"=>$fc,"id"=>$member["id"]]]);
            if(!$in_table){
                $rows[] = array("nr"=>"999","id"=>$member["id"],
                    "name"=>ucwords($member["name"]),"world"=>ucfirst($member["world"]),
                    "minions"=>"?","mounts"=>"?","all"=>"?",
                    "sync"=>"","old"=>true);
            }
        }
        return $rows;
    }
    
    
    function get_ranking_of_player($id,$world = "",$type=""){
        global $database;
        
        $players = $database->select("players",["id","name","world","last_update_date"],empty($world) ? "" : ["world[=]"=>$world] );
        $ranking = get_ranking_players($players,$type);
        $nr = 0;
        $count_befor = 0;
        foreach($ranking as $rank){
            $count_key = $rank[0];
            if($count_befor != $count_key){
                $nr++;
                $count_befor = $count_key;
            }
            
            if($rank[1]->player['id'] == $id){
                return $nr;
            }
        } 
    }
    
    function create_char_ranking($id,$world = ""){
        $cell = "";
        $gl_rank_all = get_ranking_of_player($id,$world);
        $gl_rank_minion = get_ranking_of_player($id,$world,"minions");
        $gl_rank_mounts = get_ranking_of_player($id,$world,"mounts");
        $cell .= "<b>".get_language_text("all").":</b> ".$gl_rank_all;
        $cell .= "</br>";
        $cell .= "<b>".get_language_text("minions").":</b> ".$gl_rank_minion;
        $cell .= "</br>";
        $cell .= "<b>".get_language_text("mounts").":</b> ".$gl_rank_mounts;
        return $cell;
    }
    
    
    
    function get_rarest_object($id,$table){
        global $database;
        $result = $database->query("SELECT COUNT( p_id ),m_id FROM $table GROUP BY m_id ORDER BY COUNT( p_id ) ASC")->fetchAll();
        foreach($result as $obj){
            if($database->has($table,["AND"=>["p_id"=>$id,"m_id"=>$obj['m_id']]])){
                return $obj['m_id'];
            }
        }
    }
    
    function create_rarest_thumbnail($id){
        global $database;
        global $random_id;
        $random_id_tag = "div_".$random_id;
        $random_id++;
        $title = get_language_text("rarest");
        $thumbnail = '<div class="panel panel-primary">
        <div class="panel-heading" data-toggle="collapse" data-target="#'.$random_id_tag.'" aria-expanded="true" aria-controls="'.$random_id_tag.'"><h4><b>'.
        $title.'</b></h4></div>
        <div class="panel-body">';
        $thumbnail .= '<div class="collapse  in" id="'.$random_id_tag.'">';
        
        $minion_id = get_rarest_object($id,"player_minion");
        $mount_id = get_rarest_object($id,"player_mounts");
        
        $table = '<div class="media">';
        $table .= '<div>';
        
        $minion = $database->get("minions","*",["id[=]"=>$minion_id]);
        $minion_name = ucwords($minion['name']);
        $minion_icon_url = $minion['icon_url'];
        $minion_thumbnail .= create_thumbnail_link("minion",$minion_id,$minion_name,$minion_icon_url);
        
        $table .= $minion_thumbnail;
        $table .= "</div>";
        $table .= '<div class="col-xs-0 col-md-2" style="width:auto; padding:0px; padding-left:2em">';
        $table .= "<h4>  $minion_name</h4>";
        $table .= "</div></div>";
        
        $table .= '<div class="media">';
        $table .= '<div >';
        
        $mount = $database->get("mounts","*",["id[=]"=>$mount_id]);
        $mount_name = ucwords($mount['name']);
        $mount_icon_url = $mount['icon_url'];
        $mount_thumbnail .= create_thumbnail_link("mount",$mount_id,$mount_name,$mount_icon_url);
        
        $table .= $mount_thumbnail;
        $table .= "</div>";
        $table .= '<div class="col-xs-0 col-md-2" style="width:auto; padding:0px; padding-left:2em">';
        $table .= "<h4>  $mount_name</h4></div></div>";
        
        $thumbnail .= $table;
        $thumbnail .= '</div>';
        $thumbnail .= "</div></div>";
        return $thumbnail;
    }
    
    function create_thumbnail_link($type,$id,$name,$url,$remove_div = false){
        $thumbnail = "";
        $dom_id = $type."_".$id;
        $lang = get_lang();
        $lang = $lang == "en" ? "" : $lang.".";
        
        $thumbnail .= $remove_div ? "" :'<div class="col-xs-0 col-md-2" style="width:auto; padding:0px">';
        $thumbnail .= "<a  id='$dom_id' href='https://".$lang."xivdb.com/$type/$id' class='thumbnail' >";
        $thumbnail .= "<img class='media-object' alt='$name' src=$url >";
        $thumbnail .= "</a>";
        $thumbnail .= $remove_div ? "" :"</div>";
        return $thumbnail;
    }
    
    function create_thumbnail($title,$sql_data,$type){
        global $random_id;
        $random_id_tag = "div_".$random_id;
        $random_id++;
        $count = count($sql_data);
        $thumbnail = '<div class="panel panel-primary">
        <div class="panel-heading" data-toggle="collapse" data-target="#'.$random_id_tag.'" aria-expanded="true" aria-controls="'.$random_id_tag.'"><h4><b>'.
        $title.": $count".'</b></h4></div>
        <div class="panel-body">';
        $thumbnail .= '<div class="collapse  in" id="'.$random_id_tag.'">';
        $count = 0;
        foreach($sql_data as $minion_data){
            
            $count++;
            $name = ucwords($minion_data['name']);
            $m_id = $minion_data['id'];
            $icon_url = $minion_data['icon_url'];
            $description = $minion_data['description'];
            $thumbnail .= create_thumbnail_link($type,$m_id,$name,$icon_url);
            /*
            $dom_id = $type."_".$m_id;
            $thumbnail .= '<div class="col-xs-0 col-md-2" style="width:auto; padding:0px">';
            $thumbnail .= "<a  id='$dom_id' href='https://xivdb.com/$type/$m_id' class='thumbnail' >";
            $thumbnail .= "<img class='media-object' alt='$name' src=$icon_url >";
            $thumbnail .= "</a>";
            $thumbnail .= "</div>";
            */
        }
        $thumbnail .= '</div>';
        $thumbnail .= "</div></div>";
        return $thumbnail;
    }
    
    function create_dropdown_menu($type){
        global $database;
        $methodes = get_language_text("methodes");
        $methodes_en = get_language_text("methodes","en");
        //var_dump($methodes);
        $dropdown = "";
        $class = $type."_methode";
        foreach($methodes as $i=>$methode){
            $mehtod_en = $methodes_en[$i];
            $count = $database->count($type."_method",["method[=]"=>$mehtod_en]);
            if($count > 0 || $mehtod_en == "All"){
                $methode_get = urlencode ($mehtod_en);
                $dropdown .= "<li><a id='$methode_get' class='$class'>$methode</a></li>";
            }
        }
        return $dropdown;
    }
    
    function get_methodes(){
        //global $database;
        $methodes = get_language_text("methodes");
        $methodes_en = get_language_text("methodes","en");
        $methodes_array = array();
        foreach($methodes as $i=>$methode){
            $mehtod_en = $methodes_en[$i];
            $methode_get = urlencode ($mehtod_en);
            $methodes_array[] = array('name' => $methode, 'id' => $methode_get);
        }
        return $methodes_array;
    }
    
    function insert_update_charakter_by_id($id){
        //Get charakter from lodestone
        $api = new Viion\Lodestone\LodestoneAPI();
        $character = $api->Search->Character($id);
        
        return insert_update_charakter($character);
        
    }
    
    function insert_update_charakter_by_name($name,$server){
        //Get charakter from lodestone
        $api = new Viion\Lodestone\LodestoneAPI();
        $character = $api->Search->Character($name, $server);
        
        return insert_update_charakter($character);
    }
    
    function insert_update_charakter($character){
        global $database;
        if(empty($character->id)){
            return "Could not find the charakter '$name' on server '$server'";
            exit;
        }
        $c_name = strtolower($character->name);
        $c_world = strtolower($character->world);
        $c_portrait = $database->quote($character->portrait);
        
        
        
        //Check if an charakter with the same id already exists
        $p_id = $database->get("players", "id", ["id" => $character->id]);
        $output;
        if(!$player && empty($p_id)){
            //Insert new charakter
            $database->insert("players", [
            	"id" => $character->id,
            	"name" => $c_name,
            	"world" => $c_world,
            	"title" => $character->title,
            	"portrait" => $c_portrait,
            	"race" => $character->race,
            	"clan" => $character->clan,
            	"gender" => $character->gender,
            	"nameday" => $character->nameday,
            	"guardian" => $character->guardian,
            	"grandCompany" => $character->grandCompany,
            	"freeCompany" => $character->freeCompany,
            	"freeCompanyId" => $character->freeCompanyId,
            	"last_update_date" => date("Y-m-d")
            ]);
            $output = "New charakter '$c_name' with id '$character->id' from server '$c_world' was added to database.";
        }
        else{
            //Update existing charakter
            $database->update("players", [
            	"name" => $c_name,
            	"world" => $c_world,
            	"title" => $character->title,
            	"portrait" => $c_portrait,
            	"race" => $character->race,
            	"clan" => $character->clan,
            	"gender" => $character->gender,
            	"nameday" => $character->nameday,
            	"guardian" => $character->guardian,
            	"grandCompany" => $character->grandCompany,
            	"freeCompany" => $character->freeCompany,
            	"freeCompanyId" => $character->freeCompanyId,
            	"last_update_date" => date("Y-m-d")
            ], ["id[=]"=>$character->id]);
            $output = "Charakter '$c_name' with id '$character->id' from server '$c_world' was updated.";
        }
        
        $p_id = $character->id;
        
        //Get all minions from current charakter
        $minions = $character->minions;
        insert_item_char($p_id,$minions,"minions","player_minion");
        
        $mounts = $character->mounts;
        insert_item_char($p_id,$mounts,"mounts","player_mounts");
        
        return $output;
    }
    
    function insert_item_char($p_id,$items,$table,$link_table){
        global $database;
        $datas = $database->select($table, [
        	"id",
        	"name"
        ]); 
        
        
        //Add all minions of an charakter to databese
        foreach($datas as $data)
        {
            
            $have = false;
            $db_minion = strtolower($data['name']);
            $m_id = $data["id"];
            
            foreach($items as $item){
                
                $player_item = strtolower($item['name']);
                if ($db_minion == $player_item) {
                    
                    $database->query("REPLACE INTO $link_table VALUES (
                        $p_id, 
                        $m_id);");
                    break;
                }
                
            }
        }
    }

    function get_last_id($type){
        $json = file_get_contents("https://api.xivdb.com/search?one=$type");
        $obj = json_decode($json);
        return $obj->$type->results[0]->id;
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
            
            $db_id = $database->get("minions",["id"],["id[=]"=>$id]);
            if(empty($db_id)){
                $database->insert("minions",[
                    "id"=>$obj->id,
                    "name"=>$obj->name,
                    "icon_url" => $xivdb_icon,
                    "patch" => $patch,
                    "name_en"=>$obj->name_en,
                    "name_fr"=>$obj->name_fr,
                    "name_de"=>$obj->name_de,
                    "name_ja"=>$obj->name_ja,
                    "description_en" => $obj->info1_en,
                    "description_fr" => $obj->info1_fr,
                    "description_de" => $obj->info1_de,
                    "description_ja" => $obj->info1_ja]);
            }
            else{
                $database->update("minions",[
                    "id"=>$obj->id,
                    "name"=>$obj->name,
                    "icon_url" => $xivdb_icon,
                    "name_en"=>$obj->name_en,
                    "name_fr"=>$obj->name_fr,
                    "name_de"=>$obj->name_de,
                    "name_ja"=>$obj->name_ja,
                    "description_en" => $obj->info1_en,
                    "description_fr" => $obj->info1_fr,
                    "description_de" => $obj->info1_de,
                    "description_ja" => $obj->info1_ja],
                    ["id[=]"=>$id]);
            }
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
            
            $db_id = $database->get("mounts",["id"],["id[=]"=>$id]);
            if(empty($db_id)){
                $database->insert("mounts",[
                    "id"=>$obj->id,
                    "name"=>$obj->name,
                    "icon_url" => $xivdb_icon,
                    "patch" => $patch,
                    "name_en"=>$obj->name_en,
                    "name_fr"=>$obj->name_fr,
                    "name_de"=>$obj->name_de,
                    "name_ja"=>$obj->name_ja,
                    "description_en" => $obj->info1_en,
                    "description_fr" => $obj->info1_fr,
                    "description_de" => $obj->info1_de,
                    "description_ja" => $obj->info1_ja]);
            }
            else{
                $database->update("mounts",[
                    "id"=>$obj->id,
                    "name"=>$obj->name,
                    "icon_url" => $xivdb_icon,
                    "name_en"=>$obj->name_en,
                    "name_fr"=>$obj->name_fr,
                    "name_de"=>$obj->name_de,
                    "name_ja"=>$obj->name_ja,
                    "description_en" => $obj->info1_en,
                    "description_fr" => $obj->info1_fr,
                    "description_de" => $obj->info1_de,
                    "description_ja" => $obj->info1_ja],
                    ["id[=]"=>$id]);
            }
        }
    }
    
    function create_database(){
        global $database;
        
        $database->query("CREATE TABLE minions (
            id INT NOT NULL,
            name VARCHAR(100) NOT NULL,
            icon_url VARCHAR(255) NOT NULL,
            patch VARCHAR(50) NOT NULL,
            name_en VARCHAR(100) NOT NULL,
            name_fr VARCHAR(100) NOT NULL,
            name_de VARCHAR(100) NOT NULL,
            name_ja VARCHAR(100) NOT NULL,
            description_en TEXT NOT NULL,
            description_fr TEXT NOT NULL,
            description_de TEXT NOT NULL,
            description_ja TEXT NOT NULL,
            PRIMARY KEY ( id )
            );");
        
        $database->query("CREATE TABLE mounts (
            id INT NOT NULL,
            name VARCHAR(100) NOT NULL,
            icon_url VARCHAR(255) NOT NULL,
            patch VARCHAR(50) NOT NULL,
            name_en VARCHAR(100) NOT NULL,
            name_fr VARCHAR(100) NOT NULL,
            name_de VARCHAR(100) NOT NULL,
            name_ja VARCHAR(100) NOT NULL,
            description_en TEXT NOT NULL,
            description_fr TEXT NOT NULL,
            description_de TEXT NOT NULL,
            description_ja TEXT NOT NULL,
            can_fly tinyint(1),
            PRIMARY KEY ( id )
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
                foreach($coll->methodes as $j_method){
                    $logs .= "--> Methode: $j_method->method || Desciption: - $j_method->method_description_en.</br>";
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
                if(empty($coll->methodes)){
                    $missing++;
                }
            }
            $logs .= "===> $table is missing '$missing' methods.</br>";
        }
        else{
            $logs .= "File '$file' does not exists. Could not import methodes.</br>";
        }
        
        if($readonly == "false"){
            $columns = $table == "minions" ?  ["id","name"] : ["id","name","can_fly"];
            $objects = $database->select($table,$columns);
            $methodes = array();
            foreach($objects as $obj){
                $obj_methodes = $database->select($method_table,["method","available","method_description_en",
                        "method_description_fr","method_description_de","method_description_ja"],["m_id[=]"=>$obj["id"]]);
                $obj_methodes = $obj_methodes ? $obj_methodes : array(array("method"=>null,"available"=>0,"method_description_en"=>null,
                        "method_description_fr"=>null,"method_description_de"=>null,"method_description_ja"=>null));
                $method = $table == "minions" ? array("id"=>$obj["id"],"name"=>$obj["name"],"methodes"=>$obj_methodes) 
                            : array("id"=>$obj["id"],"name"=>$obj["name"],"can_fly" => $obj["can_fly"],"methodes"=>$obj_methodes) ;
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
?>
        
        