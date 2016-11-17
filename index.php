
<?php
require_once "config.php";
require_once "helper.php";
require_once "language.php";

$lang = get_lang();
$actual_link = 'http' . ($ssl ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$base_url}?lang=" . get_lang();

$smarty = new Smarty();


$smarty->assign('title', get_language_text("title"));
$smarty->assign('lang', $lang);
$smarty->assign('subtitle',get_language_text("subtitle"));
$smarty->assign('home',get_language_text("home"));
$smarty->assign('homelink',$actual_link);
$smarty->assign('dropdown_minions',get_language_text("dropdown_minions"));
$smarty->assign('dropdown_mounts',get_language_text("dropdown_mounts"));
$smarty->assign("methodes",get_methodes());
$smarty->assign('ranking',get_language_text("ranking"));
$smarty->assign('all',get_language_text("all"));
$smarty->assign('minions',get_language_text("minions"));
$smarty->assign('mounts',get_language_text("mounts"));
$smarty->assign('search',get_language_text("search"));
$smarty->assign('char_search_placeholder',get_language_text("char_search_placeholder"));
$smarty->assign($lang."_select","selected='selected'");
$smarty->assign("my_char",get_language_text("my_char"));




$smarty->assign("content","");

$smarty->display('template/index.tpl', $data);

?>
