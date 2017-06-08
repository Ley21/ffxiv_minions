<?php
/* Smarty version 3.1.30, created on 2016-12-14 13:32:57
  from "/home/ubuntu/workspace/template/faq.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58514a09ed1040_26815112',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '139ff224f9f44461ab1f3f13102d62258e4a4ee4' => 
    array (
      0 => '/home/ubuntu/workspace/template/faq.tpl',
      1 => 1481713781,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58514a09ed1040_26815112 (Smarty_Internal_Template $_smarty_tpl) {
?>
<h1><b>FAQ<b></h1></br>
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['faq']->value, 'items');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['items']->value) {
?>
<h3><b><?php echo $_smarty_tpl->tpl_vars['items']->value['q'];?>
</b></h3>
    <p><?php echo $_smarty_tpl->tpl_vars['items']->value['a'];?>
</p>
    </br>
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
}
}
