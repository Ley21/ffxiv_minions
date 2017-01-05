<?php
    require_once "../config.php";
    require_once "../helper.php";
    
    $fc_id = $_GET['fc'];
    echo create_ranking("",$fc_id);

?>