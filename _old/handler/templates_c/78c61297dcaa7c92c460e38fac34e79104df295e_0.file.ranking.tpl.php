<?php
/* Smarty version 3.1.30, created on 2017-05-23 11:39:16
  from "/home/ubuntu/workspace/template/ranking.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_59241f6437e207_41199907',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '78c61297dcaa7c92c460e38fac34e79104df295e' => 
    array (
      0 => '/home/ubuntu/workspace/template/ranking.tpl',
      1 => 1495539452,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_59241f6437e207_41199907 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['search']->value) {?>
<div class="container">
  <div class="row">
    <div class="col-md-6">
      <b><?php echo $_smarty_tpl->tpl_vars['search_minion']->value;?>
</b>
        <select class='form-control' id='find_minion'>
        <option value=''>---------</option>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['minions']->value, 'minion');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['minion']->value) {
?>
        <option value='<?php echo $_smarty_tpl->tpl_vars['minion']->value['id'];?>
'><?php echo $_smarty_tpl->tpl_vars['minion']->value['name'];?>
</option>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

        </select>
    </div>
    <div class="col-md-6">
      <b><?php echo $_smarty_tpl->tpl_vars['search_mount']->value;?>
</b>
        <select class='form-control' id='find_mount'>
        <option value=''>---------</option>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['mounts']->value, 'mount');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['mount']->value) {
?>
        <option value='<?php echo $_smarty_tpl->tpl_vars['mount']->value['id'];?>
'><?php echo $_smarty_tpl->tpl_vars['mount']->value['name'];?>
</option>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

        </select>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <b><?php echo $_smarty_tpl->tpl_vars['not_minion']->value;?>
</b>
        <select class='form-control' id='not_minion'>
        <option value=''>---------</option>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['minions']->value, 'minion');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['minion']->value) {
?>
        <option value='<?php echo $_smarty_tpl->tpl_vars['minion']->value['id'];?>
'><?php echo $_smarty_tpl->tpl_vars['minion']->value['name'];?>
</option>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

        </select>
    </div>
    <div class="col-md-6">
      <b><?php echo $_smarty_tpl->tpl_vars['not_mount']->value;?>
</b>
        <select class='form-control' id='not_mount'>
        <option value=''>---------</option>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['mounts']->value, 'mount');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['mount']->value) {
?>
        <option value='<?php echo $_smarty_tpl->tpl_vars['mount']->value['id'];?>
'><?php echo $_smarty_tpl->tpl_vars['mount']->value['name'];?>
</option>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

        </select>
    </div>
  </div>
  <div class="row">
      </br>
      <button id="update_all_fc" type="button" class="btn btn-info"><?php echo $_smarty_tpl->tpl_vars['update_all']->value;?>
</button>
  </div>
</div>
</br>
<?php }?>

<table class='table table-condensed' id="ranking">
    <thead>
        <tr>
            <th><?php echo $_smarty_tpl->tpl_vars['tableHeaderNr']->value;?>
</th>
            <th><?php echo $_smarty_tpl->tpl_vars['tableHeaderName']->value;?>
</th>
            <th><?php echo $_smarty_tpl->tpl_vars['tableHeaderWorld']->value;?>
</th>
            <?php if ($_smarty_tpl->tpl_vars['type']->value == "minions") {?>
            <th><?php echo $_smarty_tpl->tpl_vars['tableHeaderCountMinions']->value;?>
</th>
            <?php } elseif ($_smarty_tpl->tpl_vars['type']->value == "mounts") {?>
            <th><?php echo $_smarty_tpl->tpl_vars['tableHeaderCountMounts']->value;?>
</th>
            <?php } else { ?>
            <th><?php echo $_smarty_tpl->tpl_vars['tableHeaderCountMinions']->value;?>
</th>
            <th><?php echo $_smarty_tpl->tpl_vars['tableHeaderCountMounts']->value;?>
</th>
            <th><?php echo $_smarty_tpl->tpl_vars['tableHeaderCountAll']->value;?>
</th>
            <?php }?>
            <th><?php echo $_smarty_tpl->tpl_vars['tableHeaderLastSync']->value;?>
</th>
        </tr>
    </thead>
    <tbody>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['players']->value, 'player');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['player']->value) {
?>
        <tr class='active' id='<?php echo $_smarty_tpl->tpl_vars['player']->value['id'];?>
'>
            <td><?php echo $_smarty_tpl->tpl_vars['player']->value['nr'];?>
</td>
            <td><a onclick='loadCharakter(<?php echo $_smarty_tpl->tpl_vars['player']->value['id'];?>
)'><?php echo $_smarty_tpl->tpl_vars['player']->value['name'];?>
</a></td>
            <td><?php echo $_smarty_tpl->tpl_vars['player']->value['world'];?>
</td>
            <?php if ($_smarty_tpl->tpl_vars['type']->value == "minions") {?>
            <td><?php echo $_smarty_tpl->tpl_vars['player']->value['minions'];?>
</td>
            <?php } elseif ($_smarty_tpl->tpl_vars['type']->value == "mounts") {?>
            <td><?php echo $_smarty_tpl->tpl_vars['player']->value['mounts'];?>
</td>
            <?php } else { ?>
            <td><?php echo $_smarty_tpl->tpl_vars['player']->value['minions'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['player']->value['mounts'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['player']->value['all'];?>
</td>
            <?php }?>
            <td>
                <?php if ($_smarty_tpl->tpl_vars['player']->value['old']) {?>
                <button class='btn btn-info' onclick='updateCharakter(<?php echo $_smarty_tpl->tpl_vars['player']->value['id'];?>
)'><?php echo $_smarty_tpl->tpl_vars['syncBtnText']->value;?>
</button> <?php } else { ?> <?php echo $_smarty_tpl->tpl_vars['player']->value['sync'];?>
 <?php }?>
            </td>
        </tr>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>


    </tbody>
</table><?php }
}
