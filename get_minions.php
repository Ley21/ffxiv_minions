<?php
    require_once "config.php";
    require_once "helper.php";
    require_once "language.php";
    
    $methode = $_GET["methode"];
    $methodes_en = get_language_text("methodes","en");
    
    $index = array_search($methode,$methodes_en);
    
    $minions = $database->select("minions", "*",["method[=]"=>$methode]);
    
    $tilte = get_language_text("methode")[$index];
    echo create_table($tilte,$minions);
?>