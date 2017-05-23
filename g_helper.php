<?php

    function get_lang(){
        $default = "en";
        $cookie_name = "lang";
        $lang = get_cookie($cookie_name);
        if($lang == null){
            set_lang($default);
            return $default;
        }
        return $lang;
    }
    
    function set_lang($lang){
        $cookie_name = "lang";
        set_cookie($cookie_name,$lang);
    }
    
    function set_cookie($name,$value,$days = 14){
        setcookie($name, $value, time() + (86400 * $days));
    }
    function get_cookie($name){
        if(array_key_exists($name,$_COOKIE)){
            $value = $_COOKIE[$name];
            if(!isset($value)) {
                return null;
            } else {
                return $value;
            }
        }
        return null;
    }
    
    function delete_cookie($name){
        setcookie($name, "", time() - 3600);
    }
?>