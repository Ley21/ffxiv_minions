<?php
    require_once "../helper.php";
    require_once "../config.php";
    
    $smarty = new Smarty();
    
    $faq = array();
    $faq[] = array("q"=>get_language_text("question_1"),"a"=>get_language_text("answer_1"));
    $faq[] = array("q"=>get_language_text("question_2"),"a"=>get_language_text("answer_2"));
    $faq[] = array("q"=>get_language_text("question_3"),"a"=>get_language_text("answer_3"));
    $faq[] = array("q"=>get_language_text("question_4"),"a"=>get_language_text("answer_4"));
    
    $smarty->assign('faq', $faq);
    
    $smarty->display('../template/faq.tpl');
    
?>