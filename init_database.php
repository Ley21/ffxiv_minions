<?php
    require_once "config.php";
    require_once "helper.php";
    
    // Create table for minions 
    $database->query("CREATE TABLE minions (
        id INT NOT NULL,
        name VARCHAR(100) NOT NULL,
        icon_url VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        method VARCHAR(100),
        method_description TEXT,
        PRIMARY KEY ( id )
        );");
        
    $database->query("CREATE TABLE players (
        id INT NOT NULL,
        name VARCHAR(100) NOT NULL,
        world VARCHAR(50) NOT NULL,
        portrait VARCHAR(250) NOT NULL,
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
            insert_update_minion($number);
        }
    }    
    read_write_methode();
    
?>