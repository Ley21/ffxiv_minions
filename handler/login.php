<?php
    require_once "../config.php";
    require_once "../helper.php";
    
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    $smarty = new Smarty();
    $smarty->assign("loginHeader",get_language_text("loginHeader"));
    $smarty->assign("username",get_language_text("username"));
    $smarty->assign("password",get_language_text("password"));
    $smarty->assign("login",get_language_text("login"));
    $smarty->assign("cancel",get_language_text("cancel"));
    
    
    if(!empty($username) && !empty($password)){
        $id = check_login($username,$password);
        if($id != 0){
            set_login_cookie($id);
            
            $smarty->assign("success",get_language_text("login_success_text"));
            $smarty->display('../template/loggedIn.tpl');
            $lang = get_lang();
            
            
        }else{
            $smarty->assign("warning",get_language_text("login_warning_text"));
            $smarty->display('../template/login.tpl');
        }
        
    }else if(!empty($username) || !empty($password)){
        
        $smarty->assign("warning",get_language_text("login_warning_text"));
        $smarty->display('../template/login.tpl');
    }else{
        
        $smarty->display('../template/login.tpl');
    }
    

    
    
    
?>