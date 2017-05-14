<?php
include_once("simple_html_dom.php");

class Lodestone{
    
    public static function findCharacterById($id){
        return new Character($id);
    }
    
    public static function findCharacterByNameAndServer($name,$server){
        $name = urlencode(ucwords($name));
        $server = ucfirst($server);
        $url = "http://eu.finalfantasyxiv.com/lodestone/character/?q=$name&worldname=$server";
        $html = file_get_html($url);
        $entries = $html->find("div[class=ldst__window]")[0]->find("div[class=entry]");
        foreach($entries as $entry){
            $entry_name = $entry->find("p[class=entry__name]")[0]->innertext;
            $enty_world = $entry->find("p[class=entry__world]")[0]->innertext;
            if($entry_name == urldecode($name) && $enty_world == $server){
                    $entryHtml = $entry;
                    break;
                }
        }
        if(!empty($entryHtml)){
            $id = split("/",$entryHtml->find("a")[0]->href)[3];
            return new Character($id);
        }
        return false;
    }
    
    public static function findFreeCompany($id){
        return new FreeCompany($id);
    }
}

class FreeCompany{
    public $id;
    public $members;
    
    function __construct($id) {
        $this->id = $id;
        $this->members = array();
        $this->get_members();
    }
    
    private function get_members(){
        $url = "http://eu.finalfantasyxiv.com/lodestone/freecompany/$this->id/member/";
        $html = file_get_html($url);
        $membersHtml = null;
        foreach($html->find("ul") as $subHtml){
            $elemts = $subHtml->find("li[class=entry]");
            if(count($elemts) >= 1){
                $membersHtml = $subHtml;
                break;
            }
        }
        
        foreach($membersHtml->find("li[class=entry]") as $memberHtml){
            if(!empty($memberHtml)){
                $id = split("/",$memberHtml->find("a")[0]->href)[3];
                $member = array();
                $member['id'] = $id;
                $member['name'] = $memberHtml->find("p[class=entry__name]")[0]->innertext;
                $member['world'] = $memberHtml->find("p[class=entry__world]")[0]->innertext;
                $this->members[] = $member;
            }
            
        }
    }
    
}

class Character{
    public $id;
    public $title;
    public $name;
    public $world;
    public $portrait;
    
    public $race;
    public $clan;
    public $gender;
    
    public $nameday;
    public $guardian;
    
    public $grandCompany;
    
    public $freeCompany;
    public $freeCompanyId;
    
    public $minions;
    public $mounts;
    
    private $html;
    
    function __construct($id) {
        $this->id = $id;
        $url = "http://eu.finalfantasyxiv.com/lodestone/character/";
        $this->html = file_get_html($url.$this->id);
        
        $baseCharHtml = $this->html->find("div[class=frame__chara__box]")[0];
        $this->title = $baseCharHtml->find("p[class=frame__chara__title]")[0]->innertext;
        $this->name = $baseCharHtml->find("p[class=frame__chara__name]")[0]->innertext;
        $this->world = $baseCharHtml->find("p[class=frame__chara__world]")[0]->innertext;
        
        $detailCharHtml = $this->html->find("div[class=character__detail]")[0];
        $this->portrait = strtok($detailCharHtml->find("div[class=character__detail__image]")[0]->find("img")[0]->src,"?");
        
        
        $this->parse_race();
        
        $this->nameday = $this->get_profile_data_by_title("Nameday","character-block__birth");
        $this->guardian = $this->get_profile_data_by_title("Guardian","character-block__name");
        
        $this->grandCompany = split(" /",$this->get_profile_data_by_title("Grand Company","character-block__name"))[0];
        
        $this->parse_freeCompany();
        $this->parse_minions_mounts();
    }
    
    private function get_profile_data_by_title($title,$name){
        foreach($this->html->find("div[class=character-block__box]") as $info){
            foreach($info->find("p[class=character-block__title]") as $titleHtml){
                if($titleHtml->innertext == $title){
                    return $info->find("p[class=$name]")[0]->innertext;
                }
            }
        }
        return false;
    }
    
    
    
    
    private function parse_minions_mounts(){
        $parse = function($className){
            $objects = array();
            $html = $this->html->find("div[class=$className]")[0];
            if(!empty($html)){
                foreach($html->find("li") as $objHtml){
                    $name = $objHtml->find("div[class=character__item_icon js__tooltip]")[0]->attr["data-tooltip"];
                    if(!empty($name)){
                        $objects[]['name'] = htmlspecialchars_decode($name,ENT_QUOTES);
                    }
                }
            }
            return $objects;
        };
        
        $this->minions = $parse("character__minion");
        $this->mounts = $parse("character__mounts");
    }
    
    private function parse_freeCompany(){
        foreach($this->html->find("div[class=character-block]") as $info){
            $freeCompanyHtml = $info->find("div[class=character__freecompany__name]")[0];
            if($freeCompanyHtml != null){
                $this->freeCompany = $freeCompanyHtml->find("a")[0]->innertext;
                $this->freeCompanyId = split("/",$freeCompanyHtml->find("a")[0]->href)[3];
            }
        }
    }
    
    private function parse_race(){
        //$profileHtml = $this->html->find("div[class=character__profile__data__detail]")[0];
        $raceString = $this->get_profile_data_by_title("Race/Clan/Gender","character-block__name");
        $splitArray = split("<br />",$raceString);
        $this->race = $splitArray[0];
        $splitArray2 = split(" / ",$splitArray[1]);
        $this->clan = $splitArray2[0];
        $this->gender = $splitArray2[1] == "♂" ? "male" : "female";
    }
    
    
}
?>