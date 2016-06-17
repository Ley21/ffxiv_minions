<?php
    require_once "config.php";
    
    function create_table($title,$sql_data){
        $table = '<div class="panel panel-primary">
        <div class="panel-heading">'.$title.'</div>
        <div class="panel-body">';
        $table .= '<table class="table table-striped"><tr><th>Name</th><th>Icon</th><th>Description</th></tr>';
        foreach($sql_data as $minion_data){
            $name = ucwords($minion_data['name']);
            $m_id = $minion_data['id'];
            $icon_url = "http://xivdb.com".$minion_data['icon_url'];
            $description = $minion_data['description'];
            $table .= "<tr>";
            $table .= "<td><a href='https://xivdb.com/minion/$m_id'>$name</a></td>";
            $table .= "<td><img class='media-object' src=$icon_url></td>";
            $table .= "<td>$description</td>";
            $table .= "</tr>";
        }
        $table .= "</table></div>
        </div>";
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
            $icon_url = "http://xivdb.com".$minion_data['icon_url'];
            $description = $minion_data['description'];
            $thumbnail .= '<div class="col-xs-0 col-md-1">';
            $thumbnail .= "<a href='https://xivdb.com/minion/$m_id' class='thumbnail'>";
            $thumbnail .= "<img class='media-object' alt='$name' src=$icon_url>";
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
            	"portrait" => $c_portrait,
            	"last_update_date" => date("Y-m-d")
            ]);
            $output = "New charakter '$c_name' with id '$character->id' from server '$c_world' was added to database.";
        }
        else{
            //Update existing charakter
            $database->update("players", [
            	"name" => $c_name,
            	"world" => $c_world,
            	"portrait" => $c_portrait,
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

?>