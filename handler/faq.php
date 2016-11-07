<?php
    require_once "../helper.php";
    $html = "<h1><b>FAQ<b></h1></br>";
    
    $html .= "<h3><b>".get_language_text("question_1")."</b></h3><p>".get_language_text("answer_1")."</p></br>";
    $html .= "<h3><b>".get_language_text("question_2")."</b></h3><p>".get_language_text("answer_2")."</p></br>";
    $html .= "<h3><b>".get_language_text("question_3")."</b></h3><p>".get_language_text("answer_3")."</p></br>";
    $html .= "<h3><b>".get_language_text("question_4")."</b></h3><p>".get_language_text("answer_4")."</p></br>";
    
    echo $html;
?>