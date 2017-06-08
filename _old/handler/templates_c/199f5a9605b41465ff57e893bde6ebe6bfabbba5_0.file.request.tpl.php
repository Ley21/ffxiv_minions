<?php
/* Smarty version 3.1.30, created on 2017-01-16 10:03:34
  from "/home/ubuntu/workspace/template/request.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_587c9a762dd734_77711925',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '199f5a9605b41465ff57e893bde6ebe6bfabbba5' => 
    array (
      0 => '/home/ubuntu/workspace/template/request.tpl',
      1 => 1483640568,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_587c9a762dd734_77711925 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="form-group">
    <label for="type"><?php echo $_smarty_tpl->tpl_vars['title']->value['type'];?>
</label>
    <select class="form-control" id="type">
        <option value="">----------</option>
        <option value="minions" <?php if ($_smarty_tpl->tpl_vars['type']->value == "minions") {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['title']->value['minions'];?>
</option>
        <option value="mounts" <?php if ($_smarty_tpl->tpl_vars['type']->value == "mounts") {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['title']->value['mounts'];?>
</option>
        <option value="question" <?php if ($_smarty_tpl->tpl_vars['type']->value == "question") {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['title']->value['question'];?>
</option>
    </select>
    <?php if ($_smarty_tpl->tpl_vars['type']->value == "minions") {?>
    <label for="id"><?php echo $_smarty_tpl->tpl_vars['title']->value['selectName'];?>
</label>
    <select class="form-control" id="id">
        <option value="">----------</option>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['minions']->value, 'minion');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['minion']->value) {
?>
            <option value="<?php echo $_smarty_tpl->tpl_vars['minion']->value['id'];?>
" <?php ob_start();
echo $_smarty_tpl->tpl_vars['minion']->value['id'];
$_prefixVariable1=ob_get_clean();
if ($_smarty_tpl->tpl_vars['id']->value == $_prefixVariable1) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['minion']->value['name'];?>
</option>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

    </select>
    <?php } elseif ($_smarty_tpl->tpl_vars['type']->value == "mounts") {?>
    <label for="id"><?php echo $_smarty_tpl->tpl_vars['title']->value['selectName'];?>
</label>
    <select class="form-control" id="id">
        <option value="">----------</option>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['mounts']->value, 'mount');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['mount']->value) {
?>
            <option value="<?php echo $_smarty_tpl->tpl_vars['mount']->value['id'];?>
" <?php ob_start();
echo $_smarty_tpl->tpl_vars['mount']->value['id'];
$_prefixVariable2=ob_get_clean();
if ($_smarty_tpl->tpl_vars['id']->value == $_prefixVariable2) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['mount']->value['name'];?>
</option>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

    </select>
    <?php } elseif ($_smarty_tpl->tpl_vars['type']->value == "question") {?>
        <label for='question'><?php echo $_smarty_tpl->tpl_vars['title']->value['question'];?>
(en/de):</label>
        <textarea class='form-control' id='question' rows='4' cols='50'></textarea>
    <?php }?>
    <?php if ($_smarty_tpl->tpl_vars['id']->value != '') {?>
    <label for="method"><?php echo $_smarty_tpl->tpl_vars['title']->value['existingMethod'];?>
</label>
    <select class="form-control" id="method">
        <option value="">----------</option>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['methodes']->value['exist'], 'meth');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['meth']->value) {
?>
            <option value="<?php echo $_smarty_tpl->tpl_vars['meth']->value['en'];?>
" <?php if ($_smarty_tpl->tpl_vars['method']->value['name'] == $_smarty_tpl->tpl_vars['meth']->value['en']) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['meth']->value['name'];?>
</option>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

    </select>
    <label for="new_method"><?php echo $_smarty_tpl->tpl_vars['title']->value['newMethod'];?>
</label>
    <select class="form-control" id="new_method">
        <option value="">----------</option>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['methodes']->value['new'], 'meth');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['meth']->value) {
?>
            <option value="<?php echo $_smarty_tpl->tpl_vars['meth']->value['en'];?>
" <?php if ($_smarty_tpl->tpl_vars['method']->value['name'] == $_smarty_tpl->tpl_vars['meth']->value['en']) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['meth']->value['name'];?>
</option>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

    </select>
    <?php }?>
    <?php if ($_smarty_tpl->tpl_vars['method']->value['name'] != '') {?>
        <label for='method_description_en'><?php echo $_smarty_tpl->tpl_vars['title']->value['desc'];?>
[en]:</label>
        <textarea class='form-control' id='method_description_en' rows='4' cols='50'><?php echo $_smarty_tpl->tpl_vars['method']->value['desc_en'];?>
</textarea>
        <label for='method_description_fr'><?php echo $_smarty_tpl->tpl_vars['title']->value['desc'];?>
[fr]:</label>
        <textarea class='form-control' id='method_description_fr' rows='4' cols='50'><?php echo $_smarty_tpl->tpl_vars['method']->value['desc_fr'];?>
</textarea>
        <label for='method_description_de'><?php echo $_smarty_tpl->tpl_vars['title']->value['desc'];?>
[de]:</label>
        <textarea class='form-control' id='method_description_de' rows='4' cols='50'><?php echo $_smarty_tpl->tpl_vars['method']->value['desc_de'];?>
</textarea>
        <label for='method_description_ja'><?php echo $_smarty_tpl->tpl_vars['title']->value['desc'];?>
[ja]:</label>
        <textarea class='form-control' id='method_description_ja' rows='4' cols='50'><?php echo $_smarty_tpl->tpl_vars['method']->value['desc_ja'];?>
</textarea>
    <?php }?>
    
</div><?php }
}
