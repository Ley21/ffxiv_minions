<?php
    require_once "config.php";
    
    function get_lang(){
        $lang = empty($_GET["lang"]) ? "en" : $_GET["lang"];
        return $lang;
    }
    function language_text($en,$fr,$de,$jp){
        switch(get_lang()){
            case "de":
                return empty($de) ? $en : $de;
            case "fr":
                return empty($fr) ? $en : $fr;
            case "ja":
                return empty($ja) ? $en : $ja;
            default:
            case "en":
                return $en;
        }
    }
    
    function create_table($title,$sql_data){
        $lang = get_lang();
        $table = '<div class="panel panel-primary">
        <div class="panel-heading">'.$title.'</div>
        <div class="panel-body">';
        $table .= language_text('<table class="table table-striped"><thead><tr><th>Symbol</th><th>Name</th><th>Patch</th><th>Methode</th><th>Description</th></tr></thead>',
        "",'<table class="table table-striped"><thead><tr><th>Icon</th><th>Name</th><th>Patch</th><th>Methode</th><th>Beschreibung</th></tr></thead>',"");

        $table .= "<tbody>";
        foreach($sql_data as $minion_data){
            $name = ucwords($minion_data['name_'.$lang]);
            $m_id = $minion_data['id'];
            $icon_url = $minion_data['icon_url'];
            $patch = $minion_data['patch'];
            $methode = language_text($minion_data['method_description_en'],
                $minion_data['method_description_fr'],
                $minion_data['method_description_de'],
                $minion_data['method_description_ja']);
                
            $methode_name = $minion_data['method'] ;
            $table .= "<tr>";
            $table .= "<td class='shrink'><img class='media-object' src=$icon_url></td>";
            $table .= "<td class='shrink'><a href='https://$lang.xivdb.com/minion/$m_id'>$name</a></td>";
            $table .= "<td class='shrink'>$patch</td>";
            $table .= "<td class='shrink'>$methode_name</td>";
            $table .= "<td class='expand'>$methode</td>";
            $table .= "</tr>";
        }
        $table .= "</tbody></table></div>
        </div>";
        return $table;
    }
    
    function create_ranking(){
        global $database;
        //$lang = get_lang();
        $table = language_text('<table class="table table-striped"><thead><tr><th>Nr</th><th>Name</th><th>World</th><th>Number of Minions</th></tr></thead>',"",'<table class="table table-striped"><thead><tr><th>Nr</th><th>Name</th><th>Welt</th><th>Anzahl der Minions</th></tr></thead>',"");
        $players = $database->select("players",["id","name","world"],"");
        $ranking = array();
        foreach($players as $player){
            $count = $database->count("player_minion",["p_id[=]"=>$player["id"]]);
            array_push($ranking,array($count,$player));
        }
        arsort($ranking);
        $nr = 1;
        foreach($ranking as $r_player){
            $player = $r_player[1];
            $p_id = $player['id'];
            $name = ucwords($player['name']);
            $world = ucwords($player['world']);
            $count = $r_player[0];
            $table .= "<tr><td>$nr</td><td><a onclick='loadCharakter($p_id)'>$name</a></td><td>$world</td><td>$count</td></tr>";
            $nr++;
        }
        $table .= '</table>';
        return $table;
    }
    
    function create_thumbnail($title,$sql_data){
        $thumbnail = '<div class="panel panel-primary">
        <div class="panel-heading">'.$title.'</div>
        <div class="panel-body">';
        $count = 0;
        foreach($sql_data as $minion_data){
            
            $count++;
            $name = ucwords($minion_data['name']);
            $m_id = $minion_data['id'];
            $icon_url = $minion_data['icon_url'];
            $description = $minion_data['description'];
            $thumbnail .= '<div class="col-xs-0 col-md-2" style="width:auto">';
            $thumbnail .= "<a href='https://xivdb.com/minion/$m_id' class='thumbnail' >";
            $thumbnail .= "<img class='media-object' alt='$name' src=$icon_url >";
            $thumbnail .= "</a>";
            $thumbnail .= "</div>";
        }
        $thumbnail .= "</div></div>";
        return $thumbnail;
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
            echo "Could not find the charakter '$name' on server '$server'";
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
            	"last_update_date" => date("Y-m-d")
            ], ["id[=]"=>$character->id]);
            $output = "Charakter '$c_name' with id '$character->id' from server '$c_world' was updated.";
        }
        
        //Get all minions from current charakter
        $minions = $character->minions;
        
        $datas = $database->select("minions", [
        	"id",
        	"name"
        ]); 
        
        $p_id = $character->id;
        
        //Add all minions of an charakter to databese
        foreach($datas as $data)
        {
            
            $have = false;
            $db_minion = strtolower($data['name']);
            $m_id = $data["id"];
            
            foreach($minions as $minion){
                
                $player_minion = strtolower($minion['name']);
                if ($db_minion == $player_minion) {
                    
                    $database->query("REPLACE INTO player_minion VALUES (
                        $p_id, 
                        $m_id);");
                    break;
                }
                
            }
        }
        return $output;
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
            $xivdb_icon = $database->quote("https://xivdb.com".$obj->xivdb_icon);
            
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
    
    function read_write_methode($readOnly){
        global $database;
        $file = "minions.json";
        
        //Read local file
        $json = file_get_contents($file);
        $read_minons = json_decode($json);
        
        //Update mehtode from local file
        foreach($read_minons as $minion){
            $database->update("minions",[
                "method" => $minion->method,
                "method_description_en" => $minion->method_description_en,
                "method_description_fr" => $minion->method_description_fr,
                "method_description_de" => $minion->method_description_de,
                "method_description_ja" => $minion->method_description_ja],
                ["id[=]"=>$minion->id]);
        }
        
        if(!$readOnly){
            //Save the database in the file / update new minions to file
            $minions = $database->select("minions",
                ["id","name","method","method_description_en","method_description_fr",
                "method_description_de","method_description_ja"]);
            $json_informations = json_encode($minions,JSON_PRETTY_PRINT);
            file_put_contents($file, $json_informations);
        }
    }
?>
        
        