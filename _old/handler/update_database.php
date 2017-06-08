<?php

require_once "../config.php";
require_once "../helper.php";

header("Content-Type: text/html; charset=utf-8");

$tables_exits = $database->query('SELECT 1 FROM minions LIMIT 1;') != false;

$last_minion_id = get_last_id("minions");
$last_mount_id = get_last_id("mounts");

$key = $_POST['key'];
$update = $_POST['update'];
$methodes = $_POST['method_update'];
$readonly = $_POST['readonly'];

if($_POST['update']){
    
    if($key != $external_key || empty($key)){
        echo "You are not authorised to do this action.";
        exit;
    }
    else{
        if(!$tables_exits){
            create_database();
        }else{
            //Update Table for avalible
            update_table_structur('mounts_method','available','TINYINT (1)','method');
            update_table_structur('minions_method','available','TINYINT (1)','method');
            
            //Update table for sellable, minion etc., mount etc.
            
            create_verminion_table();
            
            update_table_structur("minions","sellable","TINYINT (1)","description_ja");
            update_table_structur("minions","picture_url","varchar(255)","icon_url");
            update_table_structur("minions","summon_ja","text","description_ja");
            update_table_structur("minions","summon_de","text","description_ja");
            update_table_structur("minions","summon_fr","text","description_ja");
            update_table_structur("minions","summon_en","text","description_ja");
            update_table_structur("minions","behavior","varchar(100)","summon_ja");
            
            update_table_structur("mounts","sellable","TINYINT (1)","description_ja");
            update_table_structur("mounts","picture_url","varchar(255)","icon_url");
            update_table_structur("mounts","summon_ja","text","description_ja");
            update_table_structur("mounts","summon_de","text","description_ja");
            update_table_structur("mounts","summon_fr","text","description_ja");
            update_table_structur("mounts","summon_en","text","description_ja");
        }
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
        
        if($methodes){
            read_write_methode_new("minions","../minions.json",$readonly);
	        read_write_methode_new("mounts","../mounts.json",$readonly);
	        
        }
        
    }
}


if(!$tables_exits){
    echo "<b>THE DATABASE WAS NOT FILLED WITH TABLES CURRENTLY - ON UPDATE THE TABLES WILL BE CREATED</b></br></br>";
}

$smarty = new Smarty();
$smarty->assign('title', array(
    'last_minion_id'=>"Last Minion XIVDB ID",
    'last_mount_id'=>"Last Mount XIVDB ID",
    'key'=>"Key",
    'update'=>"Update minions & mounts",
    'methodes'=>"Update methodes",
    'readonly'=>"Dont update json file"));
$smarty->assign('last_minion_id', $last_minion_id);
$smarty->assign('last_mount_id', $last_mount_id);

$smarty->assign('update', !empty($_POST['minion']) || !empty($_POST['mount']));
$smarty->assign('methodes', empty($methodes) ? true : $methodes);
$smarty->assign('readonly', empty($readonly) ? true : $readonly);

$smarty->assign('key', $key);

$smarty->display('../template/admin.tpl');
?>
