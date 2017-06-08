<?php
    require_once "../config.php";
    require_once "../helper.php";
    require_once "../language.php";
    
    
    if($_POST["send"] == "true"){
        $html = "";
        $type = $_POST['type'];
        
        if($type == "question"){
            send_mail("[REQUEST] Question.",$_POST['question']);
            echo get_language_text("thanks_request");
            exit;
        }
        else{
        
            $id = $_POST['id'];
            $method_name = $_POST['method'];
            //$id = $database->get($type,"id",["name[=]"=>$object_name]);
            
            $befor_json = "";
            if($database->has($type."_method","method",["AND" => ["m_id[=]"=>$id,"method[=]"=>$method_name]])){
                $obj_method = $database->select($type."_method",["method","method_description_en",
                            "method_description_fr","method_description_de","method_description_ja"],
                            ["AND" => ["m_id[=]"=>$id,"method[=]"=>$method_name]])[0];
                $befor_json = json_encode($obj_method,JSON_PRETTY_PRINT);
            }
            
            $desc_en = $_POST["method_description_en"];
            $desc_fr = $_POST["method_description_fr"];
            $desc_de = $_POST["method_description_de"];
            $desc_ja = $_POST["method_description_ja"];
            
            $obj_array = array("method"=>$_POST["method"],
                "method_description_en"=>empty($desc_en) ? null : $desc_en,
                "method_description_fr"=>empty($desc_fr) ? null : $desc_fr,
                "method_description_de"=>empty($desc_de) ? null : $desc_de,
                "method_description_ja"=>empty($desc_ja) ? null : $desc_ja);
            $after_json = json_encode($obj_array,JSON_PRETTY_PRINT);
            
            $html .= "Befor:</br></br>";
            $html .= "<p>$befor_json</p>";
            $html .= "</br></br></br>";
            $html .= "After:</br></br>";
            $html .= "<p>$after_json</p>";
            
            send_mail("[REQUEST] Change for '$type' with id '$id'.",$html);
            
            echo get_language_text("thanks_request");
            exit;
        }
    }
    else{
    
        $smarty = new Smarty();
        $smarty->assign('title', array(
            'type'=>get_language_text("select_type"),
            'selectName'=>get_language_text("select_name"),
            'mounts'=>get_language_text("mounts"),
            'minions'=>get_language_text("minions"),
            'question'=>get_language_text("question"),
            'existingMethod'=>get_language_text("select_existing_method"),
            'newMethod'=>get_language_text("select_new_method"),
            'desc'=>get_language_text("method")));
        $type = $_POST['type'];
        $smarty->assign('type',$type);
        if($type == "minions" || $type == "mounts"){
            $result = $database->select($type,["id","name_".get_lang()],"ORDER BY name_".get_lang());
            $objects = array_map(function($obj){
                return array('id'=>$obj['id'],'name'=>$obj["name_".get_lang()]);
            },$result);
            
            $smarty->assign($type,$objects);
        }
        $id = $_POST['id'];
        $smarty->assign('id',$id);
        if(!empty($id)){
            $methodes = array();
            
            $methoeds_db = $database->select($type."_method","method",["m_id[=]"=>$id]);
            $methodes['exist'] = array_map(function($method){
                return array('en'=>$method,'name'=>get_method_lang($method));
            },$methoeds_db);
            
            foreach(get_language_text("methodes","en") as $method){
                if(!in_array($method,$methoeds_db) && $method != "All"){
                    $methodes['new'][] = array('en'=>$method,'name'=>get_method_lang($method)); ;
                }
            }
            $smarty->assign('methodes',$methodes);
        }
        
        $method = $_POST['method'];
        $method_obj = array('name'=>$method);
        if(!empty($method)){
            $method_db = $database->get($type."_method","*",["AND"=>["m_id[=]"=>$id,"method[=]"=>$method]]);
            $method_obj['desc_en'] = $method_db['method_description_en'];
            $method_obj['desc_fr'] = $method_db['method_description_fr'];
            $method_obj['desc_de'] = $method_db['method_description_de'];
            $method_obj['desc_ja'] = $method_db['method_description_ja'];
        }
        $smarty->assign('method',$method_obj);
        $smarty->display('../template/request.tpl');
        
        exit;
    }
?>
