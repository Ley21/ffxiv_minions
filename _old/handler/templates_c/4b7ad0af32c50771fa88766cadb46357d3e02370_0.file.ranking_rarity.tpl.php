<?php
/* Smarty version 3.1.30, created on 2017-05-09 09:21:25
  from "/home/ubuntu/workspace/template/ranking_rarity.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_59118a15ee6366_80672831',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4b7ad0af32c50771fa88766cadb46357d3e02370' => 
    array (
      0 => '/home/ubuntu/workspace/template/ranking_rarity.tpl',
      1 => 1494321666,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_59118a15ee6366_80672831 (Smarty_Internal_Template $_smarty_tpl) {
?>

<table class='table table-condensed' id="ranking">
    <thead>
        <tr>
            <th><?php echo $_smarty_tpl->tpl_vars['tableHeaderNr']->value;?>
</th>
            <th><?php echo $_smarty_tpl->tpl_vars['tableHeaderName']->value;?>
</th>
            <th><?php echo $_smarty_tpl->tpl_vars['tableHeaderPercent']->value;?>
</th>
            <th><?php echo $_smarty_tpl->tpl_vars['tableHeaderCount']->value;?>
</th>
        </tr>
    </thead>
    <tbody>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['objects']->value, 'obj');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['obj']->value) {
?>
        <tr class='active' id='<?php echo $_smarty_tpl->tpl_vars['obj']->value['type'];?>
_<?php echo $_smarty_tpl->tpl_vars['obj']->value['id'];?>
' class="">
            <td><?php echo $_smarty_tpl->tpl_vars['obj']->value['nr'];?>
</td>
            <td><a href="<?php echo $_smarty_tpl->tpl_vars['obj']->value['link'];?>
"><img src=<?php echo $_smarty_tpl->tpl_vars['obj']->value['icon'];?>
/><?php echo $_smarty_tpl->tpl_vars['obj']->value['name'];?>
</a></td>
            <td><?php echo $_smarty_tpl->tpl_vars['obj']->value['percent'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['obj']->value['count'];?>
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
