<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>FFXIV Collections</title>

    <!-- Bootstrap -->
    <link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    
    
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js "></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <script src="http://xivdb.com/tooltips.min.js"></script>
    

    
    <script type='text/javascript'>
        
        function pushUrl(type,urlData){
          window.history.pushState("object or string", "", "/"+type+"?"+urlData);
        }
        function loadCharakter(id) {
            $('#content').html("<center>Loading your Minions form database and lodestone...</center>");
            ajaxCall("char","charakter.php",getLangData() +"&"+"id="+id,function(data){});
            
        }
        
        function searchCharakter(formData) {
            $('#content').html("<center>Loading your Minions form database and lodestone...</center>");
            ajaxCall("char","charakter.php",getLangData() +"&"+formData,function(data){});
        }
        
        
        function getLangData(){
          var lang = getUrlParameter("lang");
          lang = lang ? lang : "en"
          return "lang="+lang;
        }
        
        
        function loadRanking() {
          
            $('#content').html("<center><h2>Loading Ranking...</h2></center>");
            ajaxCall("ranking","ranking.php",getLangData(),function(data){});
        }
        
        function loadMinions(submit) {
            $('#content').html("<center><h2>Loading Minions...</h2></center>");
            ajaxCall("minions","get_minions.php",submit,function(data){});
        }
        
        function loadMounts(submit) {
            $('#content').html("<center><h2>Loading Mounts...</h2></center>");
            ajaxCall("mounts","get_mounts.php",submit,function(data){});
        }
        
        function ajaxCall(baseurl,url,submitData,func){
          $.ajax
          ({ 
              url: "handler/"+url,
              data: submitData,
              type: 'get',
              success: function(data)
              {
                
                 $('#content').html(data);
                 func(data);
                 pushUrl(baseurl,submitData);
                 $('.table').DataTable();
              }
          });
        }
        
        function getUrlParameter(sParam) {
            var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;
        
            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');
        
                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : sParameterName[1];
                }
            }
        };
        
        $(document).on("submit", "form", function(e){
            e.preventDefault();
            var formSubmit = $('#char_search').serialize();
            
            searchCharakter(formSubmit);

            // document.getElementById("content").innerHTML = $.get( "charakter.php?"+formSubmit );

            return  false;
        });
        
        $(document).on("click",'#ranking',function(){
          loadRanking();
        });
        
        $(document).on("click",'.minions_methode',function(){
          loadMinions(getLangData()+"&methode="+this.id);
        });
        
        $(document).on("click",'.mounts_methode',function(){
          loadMounts(getLangData()+"&methode="+this.id);
        });
        
        $( document ).ready(function() {
            var pathArray = window.location.pathname.split( '/' );
            var last = pathArray[pathArray.length - 1];
            if(last == "char"){
                var id = getUrlParameter("id");
                if(id){
                  loadCharakter(id);
                }
                else{
                  var sPageURL = decodeURIComponent(window.location.search.substring(1));
                  searchCharakter(sPageURL);
                }
                
            }else if (last == "ranking"){
              loadRanking();
            }
            else if (last == "minions"){
              var sPageURL = decodeURIComponent(window.location.search.substring(1));
              loadMinions(sPageURL);
            }
            else if (last == "mounts"){
              var sPageURL = decodeURIComponent(window.location.search.substring(1));
              loadMounts(sPageURL);
            }
            else{
              pushUrl("",getLangData());
              $('.table').DataTable();
            }
            
        });
        
        $(document).on("change",'#lang',function(){

          // window.location.search = jQuery.query.set("lang", this.value);

          var url = window.location.href;
          var newUrl = url.replace(getLangData(),"lang="+this.value)
          window.history.pushState("object or string", "", newUrl);
          location.reload();
        });

        
    </script> 
  </head>
  <body style="background-image:url(img/background_2.jpg);background-repeat:no-repeat;background-attachment:fixed">
<?php
require_once "config.php";
require_once "helper.php";
require_once "language.php";

$lang = get_lang();
$actual_link = 'http' . ($ssl ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$base_url}?lang=" . get_lang();


?>
<div class="container" style="background-color:rgba(255, 255, 255, 0.5);">

          <p class="text-center">
<div class="row">
  <div class="col-md-12 col-md-offset-0">
<div class="jumbotron">
  <h1><?php echo get_language_text("title"); ?></h1>
  <p><?php echo get_language_text("subtitle"); ?></p>
</div>
            
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <a class="navbar-brand" href="<?php
echo $actual_link; ?>"><?php
echo get_language_text("home"); ?></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
          <?php echo get_language_text("dropdown_minions"); ?><span class="caret"></span></a>
        <ul class="dropdown-menu">
          <?php echo create_dropdown_menu("minions"); ?>
        </ul>
      </li>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
          <?php echo get_language_text("dropdown_mounts"); ?><span class="caret"></span></a>
        <ul class="dropdown-menu">
          <?php echo create_dropdown_menu("mounts"); ?>
        </ul>
      </li>
      <li><a id="ranking"><?php echo get_language_text("ranking"); ?></a></li>
      </ul>
        <form id="char_search" class="navbar-form navbar-left form-inline" action="charakter.php" method="get">
      <div class="form-group">
    <input type="text" class="form-control" placeholder="<?php echo get_language_text("char_search_placeholder"); ?>" name="name">
    <select class="form-control" name="server">
      <option value="Adamantoise">Adamantoise</option>
      <option value="Aegis">Aegis</option>
      <option value="Alexander">Alexander</option>
      <option value="Anima">Anima</option>
      <option value="Asura">Asura</option>
      <option value="Atomos">Atomos</option>
      <option value="Bahamut">Bahamut</option>
      <option value="Balmung">Balmung</option>
      <option value="Behemoth">Behemoth</option>
      <option value="Belias">Belias</option>
      <option value="Brynhildr">Brynhildr</option>
      <option value="Cactuar">Cactuar</option>
      <option value="Carbuncle">Carbuncle</option>
      <option value="Cerberus">Cerberus</option>
      <option value="Chocobo">Chocobo</option>
      <option value="Coeurl">Coeurl</option>
      <option value="Diabolos">Diabolos</option>
      <option value="Durandal">Durandal</option>
      <option value="Excalibur">Excalibur</option>
      <option value="Exodus">Exodus</option>
      <option value="Faerie">Faerie</option>
      <option value="Famfrit">Famfrit</option>
      <option value="Fenrir">Fenrir</option>
      <option value="Garuda">Garuda</option>
      <option value="Gilgamesh">Gilgamesh</option>
      <option value="Goblin">Goblin</option>
      <option value="Gungnir">Gungnir</option>
      <option value="Hades">Hades</option>
      <option value="Hyperion">Hyperion</option>
      <option value="Ifrit">Ifrit</option>
      <option value="Ixion">Ixion</option>
      <option value="Jenova">Jenova</option>
      <option value="Kujata">Kujata</option>
      <option value="Lamia">Lamia</option>
      <option value="Leviathan">Leviathan</option>
      <option value="Lich">Lich</option>
      <option value="Malboro">Malboro</option>
      <option value="Mandragora">Mandragora</option>
      <option value="Masamune">Masamune</option>
      <option value="Mateus">Mateus</option>
      <option value="Midgardsormr">Midgardsormr</option>
      <option value="Moogle">Moogle</option>
      <option value="Odin">Odin</option>
      <option value="Pandaemonium">Pandaemonium</option>
      <option value="Phoenix">Phoenix</option>
      <option value="Ragnarok">Ragnarok</option>
      <option value="Ramuh">Ramuh</option>
      <option value="Ridill">Ridill</option>
      <option value="Sargatanas">Sargatanas</option>
      <option value="Shinryu">Shinryu</option>
      <option value="Shiva">Shiva</option>
      <option value="Siren">Siren</option>
      <option value="Tiamat">Tiamat</option>
      <option value="Titan">Titan</option>
      <option value="Tonberry">Tonberry</option>
      <option value="Typhon">Typhon</option>
      <option value="Ultima">Ultima</option>
      <option value="Ultros">Ultros</option>
      <option value="Unicorn">Unicorn</option>
      <option value="Valefor">Valefor</option>
      <option value="Yojimbo">Yojimbo</option>
      <option value="Zalera">Zalera</option>
      <option value="Zeromus">Zeromus</option>
      <option value="Zodiark">Zodiark</option>
    </select>
    <button type="submit" class="btn btn-default"><?php echo get_language_text("search"); ?></button>
    </div>
    </form>
    <form class="navbar-form navbar-right form-inline">
      <select class="form-control" id="lang">
        <option value="en" <?php
echo "en" == get_lang() ? "selected='selected'" : ""; ?>>English</option>
        <option value="fr" <?php
echo "fr" == get_lang() ? "selected='selected'" : ""; ?>>Français</option>
        <option value="de" <?php
echo "de" == get_lang() ? "selected='selected'" : ""; ?>>Deutsch</option>
        <option value="ja" <?php
echo "ja" == get_lang() ? "selected='selected'" : ""; ?>>日本語</option>
      </select>
    </form>
      
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
            

    <div id="content">
      <?php
$patches = $database->query("SELECT DISTINCT patch FROM minions")->fetchAll();
$floatPatches = array();

foreach($patches as $patch) {
	$version = $patch['patch'];
	$float = floatval($version);
	array_push($floatPatches, $float);
}

sort($floatPatches);
$lastPatch = number_format(end($floatPatches) , 1, ".", "");
$latest_minions = $database->select("minions", "*", ["patch[=]" => $lastPatch]);
$latest_mounts = $database->select("mounts", "*", ["patch[=]" => $lastPatch]);
echo "<center>";
echo create_table(get_language_text("latest_minions"), $latest_minions,"minion");
echo create_table(get_language_text("latest_mounts"), $latest_mounts,"mount");
echo "</center>";
?>
    </div>
      <footer style="background-color:rgba(255, 255, 255, 0.5);" class="rounded">
        </br>
    <p class="text-center">FFXIV Collections: © 2016 Andreas Spuling</p>
    <p class="text-center">FINAL FANTASY is a registered trademark of Square Enix Holdings Co., Ltd.</br>FINAL FANTASY XIV © 2010-2016 SQUARE ENIX CO., LTD. All Rights Reserved.</p>
    
  </footer>
</div></p>
</div>



</div>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
        
  </body>

</html>