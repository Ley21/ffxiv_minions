<?php
    require_once "../config.php";
    require_once "../helper.php";
    
    echo "<b>".get_language_text("has_minion")."</b>";
    echo "<form class='form-inline'><select class='form-control' id='find_minion'>
            <option value=''>---------</option>";
    $name_att = "name_".get_lang();
    $minions = $database->select("minions", "*","ORDER BY $name_att");
    foreach($minions as $minion){
        $id = $minion["id"];
        $name = $minion[$name_att];
        echo "<option value='$id'>$name</option>";
    }
    echo "</select></form></br>";
      
    //$free_company = $_GET['fc'];
    $fc_id = $_GET['fc'];
    echo create_ranking("",$fc_id);

?>