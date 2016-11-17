<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>FFXIV Collector</title>

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
    <script src="js/functions.js"></script>
    <script type='text/javascript'>
        var XIVDBTooltips;
        var valid = getUrlParameter("lang") === undefined || getUrlParameter("lang") == "en";
        var xivdb_tooltip_base_url = valid  ? 'https://xivdb.com' : 'https://'+getUrlParameter("lang")+".xivdb.com";
        var xivdb_tooltips =
        {
            // the XIVDB server to query (this will be https soon)
            xivdb: xivdb_tooltip_base_url,
        
            // the language the tooltips should be
            language: getUrlParameter("lang"),
            
            seturlname: false,
        
            // tooltips require JQuery and "check" it, this can add a half a second delay
            // if you have jquery already on your site, set this false
            jqueryEmbed: false,
        
            // whether to include the content icon next to the link
            seturlicon: false
        };
    </script>
    <script src="https://xivdb.com/tooltips.js"></script>
    <script type='text/javascript'>

        
        $(document).on("submit", "form", function(e){
            e.preventDefault();
            var formSubmit = $('#char_search').serialize();
            
            searchCharakter(getLangData()+"&"+formSubmit);

            // document.getElementById("content").innerHTML = $.get( "charakter.php?"+formSubmit );

            return  false;
        });
        
        $(document).on("click",'.ranking_dropdown',function(){
          loadRanking(getLangData()+"&type="+this.id);
        });
        $(document).on("click",'.freeCompany',function(){
          loadFreeCompany(getLangData()+"&fc="+this.id);
        });
        
        $(document).on("click",'.minions_methode',function(){
          loadMinions(getLangData()+"&methode="+this.id);
        });
        
        $(document).on("click",'.mounts_methode',function(){
          loadMounts(getLangData()+"&methode="+this.id);
        });
        $(document).on("click",'#char_button',function(){
          browserAs();
        });
        
        $(document).on("click",'#user',function(){
          var p_id = getCookie("player_id");
          loadCharakter(p_id);
        });
        $(document).on("click",'#faq',function(){
          loadFaq(getLangData());
        });
        
        $(document).ready(function() {
            browserView()
            var pathArray = window.location.pathname.split( '/' );
            var data = decodeURIComponent(window.location.search.substring(1));
            var last = pathArray[pathArray.length - 1];
            if(last == "char"){
                var id = getUrlParameter("id");
                if(id){
                  loadCharakter(id);
                }
                else{
                  searchCharakter(data);
                }
                
                
            }else if (last == "ranking"){
              loadRanking(data);
            }
            else if (last == "minions"){
              loadMinions(data);
            }
            else if (last == "mounts"){
              loadMounts(data);
            }
            else if (last == "freeCompany"){
              loadFreeCompany(data);
            }
            else if (last == "faq"){
              loadFaq(data);
            }
            else{
              pushUrl("",getLangData());
              $('.table').DataTable();
            }
            
        });
        $(document).on('draw.dt','.table', function () {
            
            XIVDBTooltips.get();
            browserView();
        } );
        
        $(document).on("change",'#lang',function(){

          // window.location.search = jQuery.query.set("lang", this.value);

          var url = window.location.href;
          var newUrl = url.replace(getLangData(),"lang="+this.value)
          window.history.pushState("object or string", "", newUrl);
          location.reload();
        });

        $(document).on("change",'#find_minion',function(){
          $( "tr" ).removeClass( "success" );
          $.ajax
          ({ 
              url: "handler/find_player_minions.php",
              data: "minion="+this.value,
              type: 'get',
              success: function(data)
              {
                var obj = JSON.parse(data);
                obj.forEach(function(id){
                  $("#"+id ).addClass( "success" );
                });      
              }
          });
        });
        $(document).on('show.bs.modal','#updateDB', function () {
          var modal = $(this);
          var body = modal.find('.modal-body');
          basicAjaxCall("update_database.php","",function(data){},get_language_text("get_latest_ids"),body);
          
        });
        
        $(document).on('show.bs.modal','#request', function () {
          var modal = $(this);
          var body = modal.find('.modal-body');
          basicAjaxCall("request_change.php",getLangData(),function(data){},"",body);
        });
        
        $(document).on('click','#update_button', function () {
          var modal = $('#updateDB');
          var body = modal.find('.modal-body');
          var key = $('#key').val();
          var update = $('#update').is(':checked');
          
          if(update == true){
            var minion_id = parseInt($('#minion_id').text());
            async_database_update(minion_id,"minion","update_minions");
            
            var mount_id = parseInt($('#mount_id').text());
            async_database_update(mount_id,"mount","update_mounts");
          }
          var method_update = $('#method_update').is(':checked');
          var readonly = $('#readonly').is(':checked');
          if(method_update){
            async_call(function(){
              basicAjaxCall("update_database.php","key="+key+"&update=true&method_update="+method_update+"&readonly="+readonly,
                function(data){},get_language_text("read_methodes"),body,"post");
            },function(){});
            
          }
          
          
          
        })
        
        $(document).on("change",'#type',function(){
          update_request_modal(false);
          
        });
        $(document).on("change",'#obj_name',function(){
          update_request_modal(false);
        });
        $(document).on("change",'#method',function(){
          update_request_modal(false);
          $("#send_button").removeClass("disabled");
        });
        $(document).on("change",'#new_method',function(){
          update_request_modal(false);
          $("#send_button").removeClass("disabled");
        });
        $(document).on('click','#send_button', function () {
          update_request_modal(true);
          $(this).addClass("disabled");
        });
        
        function update_request_modal(send){
          var modal = $('#request');
          var body = modal.find('.modal-body');
          
          var type = $('#type').val() === undefined ? "": $('#type').val() ;
          var obj_name = $('#obj_name').val() === undefined || $('#obj_name').val() == null ? "": $('#obj_name').val() ;
          var method = $('#method').val() === undefined ? $('#new_method').val() : $('#method').val();
          method = method === undefined ? "" : method;
          var method_description_en = $('#method_description_en').val();
          var method_description_fr = $('#method_description_fr').val();
          var method_description_de = $('#method_description_de').val();
          var method_description_ja = $('#method_description_ja').val();
          var submit = getLangData()+"&send="+send+"&type="+type+"&obj_name="+obj_name+"&method="+method
            +"&method_description_en="+method_description_en+"&method_description_fr="+method_description_fr
            +"&method_description_de="+method_description_de+"&method_description_ja="+method_description_ja;
          basicAjaxCall("request_change.php",submit,function(data){},"",body,"post");
        }
        
        
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
<div class="row">
<div class="col-md-1"> </div>
<div class="col-md-10"><center>
<div id='container' class="container-fluid" style="background-color:rgba(255, 255, 255, 0.5);">

          <p class="text-center">


  
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
      
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
          <?php echo get_language_text("ranking"); ?><span class="caret"></span></a>
        <ul class="dropdown-menu">
          <?php 
            echo "<li><a id='all' class='ranking_dropdown'>".get_language_text("all")."</a></li>"; 
            echo "<li><a id='minions' class='ranking_dropdown'>".get_language_text("minions")."</a></li>";
            echo "<li><a id='mounts' class='ranking_dropdown'>".get_language_text("mounts")."</a></li>";
          ?>
        </ul>
      </li>
      <li><a id='faq'>FAQ</a></li>
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
      <form class="navbar-form navbar-right form-inline">
    <button type="button" class="btn btn-primary" id="user" style="display: none;">
    <span class="glyphicon glyphicon-user" aria-hidden="true"/><?php echo " ".get_language_text("my_char");?>
    </button>
    </form>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
            
<div class="rounded_row" >
    <div id="content" style="padding:20px">
      <?php
$patches = $database->query("SELECT DISTINCT patch FROM minions")->fetchAll();
$floatPatches = array();

foreach($patches as $patch) {
	$version = $patch['patch'];
	$float = floatval($version);
	array_push($floatPatches, $float);
}

sort($floatPatches);
$lastPatch = number_format(end($floatPatches) , 2, ".", "");
$lastPatch = (float)$lastPatch;
$latest_minions = $database->select("minions", "*", ["patch[=]" => $lastPatch]);
$latest_mounts = $database->select("mounts", "*", ["patch[=]" => $lastPatch]);
echo "<center>";
echo create_table(get_language_text("latest_minions"), $latest_minions,"minion");
echo create_table(get_language_text("latest_mounts"), $latest_mounts,"mount");
echo "</center>";
?>
    </div>
    </div>
<div id="updateDB" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Update database from xivdb</h4>
      </div>
      <div class="modal-body">
        <p></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="update_button" class="btn btn-primary">Update</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="request" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Request a change for an object</h4>
      </div>
      <div class="modal-body">
        <p></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="send_button" class="btn btn-primary disabled">Send</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
      <footer style="background-color:rgba(255, 255, 255, 0.5);" class="rounded">
        </br>
    <p class="text-center">FFXIV Collector: © 2016 Andreas Spuling (<a href='/char?id=2215586'>Ley Sego</a> on Shiva)</p>
    <p class="text-center">Project on GitHub <a href='https://github.com/Ley21/ffxiv_minions'>Ley21/ffxiv_minions</a>.</p>
    <p class="text-center"><a data-toggle="modal" data-target="#updateDB">Update</a> - <a data-toggle="modal" data-target="#request">Request Change</a></p>
    <p class="text-center">FINAL FANTASY is a registered trademark of Square Enix Holdings Co., Ltd.</br>FINAL FANTASY XIV © 2010-2016 SQUARE ENIX CO., LTD. All Rights Reserved.</p>
    
  </footer>
</p>

</div>
</center>
</div>
<div class="col-md-1"></div>



</div>

<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
    
</body>

</html>