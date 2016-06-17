<?php
    require_once "config.php";
    
    // Create table for minions 
    $database->query("CREATE TABLE minions (
        id INT NOT NULL,
        name VARCHAR(100) NOT NULL,
        icon_url VARCHAR(100) NOT NULL,
        description TEXT NOT NULL,
        method VARCHAR(100),
        method_description TEXT,
        PRIMARY KEY ( id )
        );");
        
    $database->query("CREATE TABLE players (
        id INT NOT NULL,
        name VARCHAR(100) NOT NULL,
        world VARCHAR(50) NOT NULL,
        portrait VARCHAR(100) NOT NULL,
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
     
     
    $updateMinions = $_GET["update"];
    
    if($updateMinions){
        //Get all minions and insert or update the informations from xivdb
        $first = 1;
        $last = $_GET["last"];
        foreach(range($first, $last) as $number) {
            $json = file_get_contents("https://api.xivdb.com/minion/$number");
            $obj = json_decode($json);
            if(empty($obj->id)){
                echo "Minion with number '$number' does not exists.";
            }
            else{
                $name = $database->quote($obj->name);
                $xivdb_icon = $database->quote($obj->xivdb_icon);
                $info = $database->quote($obj->info1);
                
                $database->query("REPLACE INTO minions VALUES (
                    $obj->id, 
                    $name, 
                    $xivdb_icon,
                    $info,
                    '',
                    '');");
                    //var_dump($database->error());
            }
        }
    }
    
?>