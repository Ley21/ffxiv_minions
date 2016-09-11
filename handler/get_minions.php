<?php
    require_once "../config.php";
    require_once "../helper.php";
    require_once "../language.php";
    
    $methode = $_GET["methode"];
    $methodes_en = get_language_text("methodes","en");
    
    $index = array_search($methode,$methodes_en);
    
    if($methode == "All"){
        $minions = $database->select("minions", "*","");
    }
    else{
        
        $minions = $database->select("minions",["[>]minions_method"=>["id"=>"m_id"]], "*",["method[=]"=>$methode]);
    }
    
    $title = get_language_text("methodes")[$index];
    echo "<center>";
    echo create_table($title,$minions,"minion",$methode);
    echo "</center>";
?>