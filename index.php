
<?php
require_once "config.php";
require_once "helper.php";
require_once "language.php";

$lang = get_lang();
$actual_link = 'http' . ($ssl ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$base_url}?lang=" . get_lang();

//Init index page with informations
$smarty = new Smarty();

$smarty->assign('title', get_language_text("title"));
$smarty->assign('lang', $lang);
$smarty->assign('subtitle',get_language_text("subtitle"));
$smarty->assign('home',get_language_text("home"));
$smarty->assign('homelink',$actual_link);
$smarty->assign('dropdown_minions',get_language_text("dropdown_minions"));
$smarty->assign('dropdown_mounts',get_language_text("dropdown_mounts"));
$smarty->assign("methodes",array('minion'=>get_methodes("minions"),'mount'=>get_methodes("mounts")));
$smarty->assign('ranking',get_language_text("ranking"));
$smarty->assign('all',get_language_text("all"));
$smarty->assign('minions',get_language_text("minions"));
$smarty->assign('mounts',get_language_text("mounts"));
$smarty->assign('search',get_language_text("search"));
$smarty->assign('char_search_placeholder',get_language_text("char_search_placeholder"));
$smarty->assign($lang."_select","selected='selected'");
$smarty->assign("my_char",get_language_text("my_char"));
$smarty->assign("startpage",true);

//Get latest patch number from database
$lastPatch = get_latest_patch();

//Get latest minions/mounts
$content = create_table(get_language_text("latest_minions"), "minion",true);

$content .= create_table(get_language_text("latest_mounts"), "mount",true);

//Add latest minions/mounts to content
$smarty->assign("content",$content);

$count_today = $database->query("SELECT COUNT( * ) FROM players WHERE DATE( last_update_date ) = DATE( NOW( ) )")->fetchAll()[0][0];

$smarty->assign("count_today",$count_today);

//Show index
$smarty->display('template/index.tpl');

?>
