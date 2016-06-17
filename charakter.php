
<?php

    require_once "config.php";
    
    //Get informations from get.
    $name = strtolower($_GET["name"]);
    $server = strtolower($_GET["server"]);
    $esc_name = $database->quote($name);
    
    //Get player from database if exists.
    $player = $database->select('players',
        "*", ["name[=]"=> $name]);
    
    //Check if the last update date is longer then one day ago.
    if($player[0]['last_update_date'] != date("Y-m-d")){
        //Get charakter from lodestone
        $api = new Viion\Lodestone\LodestoneAPI();
        $character = $api->Search->Character($name, $server);
        
        if(empty($character->id)){
            echo "Could not find the charakter '$name' on server '$server'";
            exit;
        }
        $c_name = strtolower($character->name);
        $c_world = strtolower($character->world);
        $c_portrait = $database->quote($character->portrait);
        
        //Check if an charakter with the same id already exists
        $p_id = $database->get("players", "id", ["id" => $character->id]);
        if(!$player && empty($p_id)){
            //Insert new charakter
            $database->insert("players", [
            	"id" => $character->id,
            	"name" => $c_name,
            	"world" => $c_world,
            	"portrait" => $c_portrait,
            	"last_update_date" => date("Y-m-d")
            ]);
        }
        else{
            //Update existing charakter
            $database->update("players", [
            	"name" => $c_name,
            	"world" => $c_world,
            	"portrait" => $c_portrait,
            	"last_update_date" => date("Y-m-d")
            ], ["id[=]"=>$character->id]);
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
            
    }
    
    //Get player id
    $player = $database->select('players',
        "*", ["name[=]"=> $name]);
    $p_id = $player[0]["id"];
    $p_name = ucwords($player[0]["name"]);
    
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
    echo "<h2>$p_name</h2></br>";          
    echo create_table("Owened Minions",$exitsts_minions);
    echo create_table("Missing Minions",$missing_minions);
    echo "</center>";

    function create_table($title,$sql_data){
        $table = '<div class="panel panel-primary">
        <div class="panel-heading">'.$title.'</div>
        <div class="panel-body">';
        $table .= '<table class="table table-striped"><tr><th>Name</th><th>Icon</th><th>Description</th></tr>';
        foreach($sql_data as $minion_data){
            $name = ucwords($minion_data['name']);
            $icon_url = "http://xivdb.com".$minion_data['icon_url'];
            $description = $minion_data['description'];
            $table .= "<tr>";
            $table .= "<td>$name</td>";
            $table .= "<td><img class='media-object' src=$icon_url></td>";
            $table .= "<td>$description</td>";
            $table .= "</tr>";
        }
        $table .= "</table></div>
        </div>";
        return $table;
    }
    
    
?>