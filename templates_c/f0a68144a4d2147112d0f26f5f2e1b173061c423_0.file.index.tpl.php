<?php
/* Smarty version 3.1.30, created on 2016-11-17 12:24:20
  from "/home/ubuntu/workspace/template/index.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_582da1745b3749_32234651',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f0a68144a4d2147112d0f26f5f2e1b173061c423' => 
    array (
      0 => '/home/ubuntu/workspace/template/index.tpl',
      1 => 1479385454,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_582da1745b3749_32234651 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="<?php echo $_smarty_tpl->tpl_vars['lang']->value;?>
">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>

    <!-- Bootstrap -->
    <link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    
    
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <?php echo '<script'; ?>
 src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"><?php echo '</script'; ?>
>
      <?php echo '<script'; ?>
 src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"><?php echo '</script'; ?>
>
    <![endif]-->
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <?php echo '<script'; ?>
 src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js "><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="js/functions.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="js/index.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="https://xivdb.com/tooltips.js"><?php echo '</script'; ?>
>
  </head>
  <body style="background-image:url(img/background_2.jpg);background-repeat:no-repeat;background-attachment:fixed">

<div class="row">
<div class="col-md-1"> </div>
<div class="col-md-10"><center>
<div id='container' class="container-fluid" style="background-color:rgba(255, 255, 255, 0.5);">

          <p class="text-center">


  
<div class="jumbotron">
  <h1><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h1>
  <p><?php echo $_smarty_tpl->tpl_vars['subtitle']->value;?>
</p>
</div>
            
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <a class="navbar-brand" href="<?php echo $_smarty_tpl->tpl_vars['homelink']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['home']->value;?>
</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
          <?php echo $_smarty_tpl->tpl_vars['dropdown_minions']->value;?>
<span class="caret"></span></a>
        <ul class="dropdown-menu">
          <?php echo '<?php ';?>echo create_dropdown_menu("minions"); <?php echo '?>';?>
        </ul>
      </li>

      
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
          <?php echo '<?php ';?>echo get_language_text("dropdown_mounts"); <?php echo '?>';?><span class="caret"></span></a>
        <ul class="dropdown-menu">
          <?php echo '<?php ';?>echo create_dropdown_menu("mounts"); <?php echo '?>';?>
        </ul>
      </li>
      
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
          <?php echo '<?php ';?>echo get_language_text("ranking"); <?php echo '?>';?><span class="caret"></span></a>
        <ul class="dropdown-menu">
          <?php echo '<?php 
            ';?>echo "<li><a id='all' class='ranking_dropdown'>".get_language_text("all")."</a></li>"; 
            echo "<li><a id='minions' class='ranking_dropdown'>".get_language_text("minions")."</a></li>";
            echo "<li><a id='mounts' class='ranking_dropdown'>".get_language_text("mounts")."</a></li>";
          <?php echo '?>';?>
        </ul>
      </li>
      <li><a id='faq'>FAQ</a></li>
      </ul>
        <form id="char_search" class="navbar-form navbar-left form-inline" action="charakter.php" method="get">
      <div class="form-group">
    <input type="text" class="form-control" placeholder="<?php echo '<?php ';?>echo get_language_text("char_search_placeholder"); <?php echo '?>';?>" name="name">
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
    <button type="submit" class="btn btn-default"><?php echo '<?php ';?>echo get_language_text("search"); <?php echo '?>';?></button>
    </div>
    </form>
    
    <form class="navbar-form navbar-right form-inline">
      <select class="form-control" id="lang">
        <option value="en" <?php echo '<?php
';?>echo "en" == get_lang() ? "selected='selected'" : ""; <?php echo '?>';?>English</option>
        <option value="fr" <?php echo '<?php
';?>echo "fr" == get_lang() ? "selected='selected'" : ""; <?php echo '?>';?>Français</option>
        <option value="de" <?php echo '<?php
';?>echo "de" == get_lang() ? "selected='selected'" : ""; <?php echo '?>';?>Deutsch</option>
        <option value="ja" <?php echo '<?php
';?>echo "ja" == get_lang() ? "selected='selected'" : ""; <?php echo '?>';?>日本語</option>
      </select>
    </form>
      <form class="navbar-form navbar-right form-inline">
    <button type="button" class="btn btn-primary" id="user" style="display: none;">
    <span class="glyphicon glyphicon-user" aria-hidden="true"/><?php echo '<?php ';?>echo " ".get_language_text("my_char");<?php echo '?>';?>
    </button>
    </form>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
            
<div class="rounded_row" >
    <div id="content" style="padding:20px">
      <?php echo '<?php
';?>$patches = $database->query("SELECT DISTINCT patch FROM minions")->fetchAll();
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
<?php echo '?>';?>
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
<?php echo '<script'; ?>
 src="vendor/twbs/bootstrap/dist/js/bootstrap.min.js"><?php echo '</script'; ?>
>
    
</body>

</html><?php }
}
