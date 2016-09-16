<?php

require_once "../config.php";
require_once "../helper.php";

header("Content-Type: text/html; charset=utf-8");

$last_minion_id = get_last_id("minions");
$last_mount_id = get_last_id("mounts");

$key = $_POST['key'];
if($_POST['update']){
    if($key != $external_key || empty($key)){
        echo "You are not authorised to do this action.";
        exit;
    }
    else{
        $minion_id = $_POST['minion'];
        $mount_id = $_POST['mount'];
        if(!empty($minion_id)){
            
            $last_minion_id = $minion_id + 50 > $last_minion_id ? $last_minion_id : $minion_id + 50;
            foreach(range($minion_id, $last_minion_id) as $number) {
                insert_update_minion($number);
            }
        }
        if(!empty($mount_id)){
            $last_mount_id = $mount_id + 50 > $last_mount_id ? $last_mount_id : $mount_id + 50;
            foreach(range($mount_id, $last_mount_id) as $number) {
                insert_update_mount($number); 
            }
        }
        
        if($_POST['method_update']){
            $readonly = $_POST["readonly"];
            read_write_methode_new("minions","../minions.json",$readonly);
	        read_write_methode_new("mounts","../mounts.json",$readonly);
	        
        }
        
    }
}

$methode_update_checked = $_POST['method_update'] ? "checked" : "";
$readonly_checked = $_POST['readonly']  || empty($_POST['readonly']) ? "" : "checked";
$minion_checked = !empty($_POST['minion']) || !$_POST['update']? "checked" : "";


echo "Last minion id: '<a id='minion_id'>$last_minion_id</a>'</br>";
echo "Last mount id: '<a id ='mount_id'>$last_mount_id</a>'</br>";
echo "Key: <input id='key' value='$key'></input></br>";
echo "Update minions and mounts: <input type='checkbox' id='update' $minion_checked></input></br>";
echo "Read methodes: <input type='checkbox' id='method_update' $methode_update_checked></input></br>";
echo "Write json file: <input type='checkbox' id='readonly' $readonly_checked></input></br>";

?>
