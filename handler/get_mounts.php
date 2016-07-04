<?php
    require_once "../config.php";
    require_once "../helper.php";
    require_once "../language.php";
    
    $methode = $_GET["methode"];
    $methodes_en = get_language_text("methodes","en");
    
    $index = array_search($methode,$methodes_en);
    
    if($methode == "All"){
        $minions = $database->select("mounts", "*","");
    }
    else{
        $minions = $database->select("mounts", "*",["method[=]"=>$methode]);
    }
    
    $title = get_language_text("methodes")[$index];
    echo "<center>";
    echo create_table($title,$minions,"mount");
    echo "</center>";
?>