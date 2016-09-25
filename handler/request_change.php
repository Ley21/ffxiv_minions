<?php
    require_once "../config.php";
    require_once "../helper.php";
    require_once "../language.php";
    
    
    if($_POST["send"] == "true"){
        $html = "";
        $type = $_POST['type'];
        $object_name = $_POST['obj_name'];
        $method_name = $_POST['method'];
        $id = $database->get($type,"id",["name[=]"=>$object_name]);
        
        $befor_json = "";
        if($database->has($type."_method","method",["AND" => ["m_id[=]"=>$id,"method[=]"=>$method_name]])){
            $obj_method = $database->select($type."_method",["method","method_description_en",
                        "method_description_fr","method_description_de","method_description_ja"],
                        ["AND" => ["m_id[=]"=>$id,"method[=]"=>$method_name]])[0];
            $befor_json = json_encode($obj_method,JSON_PRETTY_PRINT);
        }
        
        
        $obj_array = array("method"=>$_POST["method"],
            "method_description_en"=>$_POST["method_description_en"],
            "method_description_fr"=>$_POST["method_description_fr"],
            "method_description_de"=>$_POST["method_description_de"],
            "method_description_ja"=>$_POST["method_description_ja"]);
        $after_json = json_encode($obj_array,JSON_PRETTY_PRINT);
        
        $html .= "Befor:</br></br>";
        $html .= "<p>$befor_json</p>";
        $html .= "</br></br></br>";
        $html .= "After:</br></br>";
        $html .= "<p>$after_json</p>";
        
        send_mail("[REQUEST] Change for '$type' with id '$id' and name '$object_name'.",str_replace("\/","/",$html));
        
        echo get_language_text("thanks_request");
        exit;
    }
    else{
    
        $html = "";
        $type = $_POST['type'];
        $minion_selected = $type == "minions"?"selected":"";
        $mount_selected = $type == "mounts"?"selected":"";
        
        $html .= "<div class='form-group'>";
        $html .= "<label for='type'>".get_language_text("select_type")."</label>";
        $html .= "<select class='form-control' id='type'>";
        $html .= "<option value=''>----------</option>";
        $html .= "<option value='minions' $minion_selected>".get_language_text("minions")."</option>";
        $html .= "<option value='mounts' $mount_selected>".get_language_text("mounts")."</option>";
        $html .= "</select>";
        $html .= "<label for='obj_name'>".get_language_text("select_name")."</label>";
        $html .= "<select class='form-control' id='obj_name'>";
        $object_name = $_POST["obj_name"];
        if(!empty($type)){
            $objects = get_list_of($type);
            $html .= "<option value=''>----------</option>";
            //var_dump($objects);
            foreach($objects as $obj){
                $column = "name_".get_lang();
                $value = $obj['name'];
                $selected = $value == $object_name ? "selected":"";
                $text = $obj[$column];
                $html .= "<option value='$value' $selected>$text</option>";
            }
            
        }
        $html .= "</select>";
        
        
        if(!empty($object_name)){
            $id = $database->get($type,"id",["name[=]"=>$object_name]);
            $method_name = $_POST["method"];
            
            if($database->has($type."_method",["m_id[=]"=>$id])){
                $html .= "<label for='method'>".get_language_text("select_existing_method")."</label>";
                $html .= "<select class='form-control' id='method'>";
                $html .= "<option value=''>----------</option>";
                $methodes = $database->select($type."_method","method",["m_id[=]"=>$id]);
                foreach($methodes as $method){
                    $method_text = get_language_method($method);
                    $selected = $method_name == $method ? "selected" : "";
                    $html .= "<option value='$method' $selected>$method_text</option>";            }
                $html .= "</select>";
            }
            $html .= "<label for='new_method'>".get_language_text("select_new_method")."</label>";
            $html .= "<select class='form-control' id='new_method'>";
            $html .= "<option value=''>----------</option>";
            
            foreach(get_language_text("methodes","en") as $method){
                if(!$database->has($type."_method",["AND" => ["m_id[=]"=>$id,"method[=]"=>$method]])){
                    $method_text = get_language_method($method);
                    $selected = $method_name == $method ? "selected" : "";
                    $html .= "<option value='$method' $selected>$method_text</option>";
                }
            }
            $html .= "</select>";
            
            if(!empty($method_name)){
                if($database->has($type."_method",["AND" => ["m_id[=]"=>$id,"method[=]"=>$method_name]])){
                    $obj = $database->select($type."_method","*",["AND" => ["m_id[=]"=>$id,"method[=]"=>$method_name]])[0];
                    $value_en = $obj["method_description_en"];
                    $value_fr = $obj["method_description_fr"];
                    $value_de = $obj["method_description_de"];
                    $value_ja = $obj["method_description_ja"];
                }
                $html .= "<label for='method_description_en'>".get_language_text("method","en")."[en]:</label>";
                $html .= "<textarea class='form-control' id='method_description_en' rows='4' cols='50'>$value_en</textarea>";
                $html .= "<label for='method_description_fr'>".get_language_text("method","fr")."[fr]:</label>";
                $html .= "<textarea class='form-control' id='method_description_fr' rows='4' cols='50'>$value_fr</textarea>";
                $html .= "<label for='method_description_de'>".get_language_text("method","de")."[de]:</label>";
                $html .= "<textarea class='form-control' id='method_description_de' rows='4' cols='50'>$value_de</textarea>";
                $html .= "<label for='method_description_ja'>".get_language_text("method","ja")."[ja]:</label>";
                $html .= "<textarea class='form-control' id='method_description_ja' rows='4' cols='50'>$value_ja</textarea>";
            }
        }
    
        $html .= "</div>";
        echo $html;
        exit;
    }
?>
