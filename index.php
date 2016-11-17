
<?php
require_once "config.php";
require_once "helper.php";
require_once "language.php";

$lang = get_lang();
$actual_link = 'http' . ($ssl ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$base_url}?lang=" . get_lang();

//$data = array('lang'=>$lang, 'title'=>get_language_text("title"),'subtitle'=>get_language_text("subtitle"));
$smarty->assign('title', get_language_text("title"));
$smarty->assign('lang', $lang);
$smarty->assign('subtitle',get_language_text("subtitle"));
$smarty->assign('home',get_language_text("home"));
$smarty->assign('homelink',$actual_link);
$smarty->assign('dropdown_minions',get_language_text("dropdown_minions"));




$smarty->display('template/index.tpl', $data);

?>
