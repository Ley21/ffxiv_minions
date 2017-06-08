<?php
/* Smarty version 3.1.30, created on 2017-05-08 07:22:05
  from "/home/ubuntu/workspace/template/table.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_59101c9d3ef016_37277665',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'aa4e7580560a60913028c6697086729fa9bf9f1d' => 
    array (
      0 => '/home/ubuntu/workspace/template/table.tpl',
      1 => 1494227699,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_59101c9d3ef016_37277665 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h4><b><?php echo $_smarty_tpl->tpl_vars['tableTitle']->value;?>
: <?php echo $_smarty_tpl->tpl_vars['tableCount']->value;?>
</b></h4>
    </div>
    <div class="panel-body">
        <table class='table table-striped' id='<?php echo $_smarty_tpl->tpl_vars['tableId']->value;?>
'>
            <thead>
                <tr>
                    <th><?php echo $_smarty_tpl->tpl_vars['tableHeadIconTitle']->value;?>
</th>
                    <th><?php echo $_smarty_tpl->tpl_vars['tableHeadNameTitle']->value;?>
</th>
                    <th><?php echo $_smarty_tpl->tpl_vars['tableHeadPatchTitle']->value;?>
</th>
                    <?php if ('tableHeadCanFlyTitle' == '') {?>
                    <th><?php echo $_smarty_tpl->tpl_vars['tableHeadCanFlyTitle']->value;?>
</th>
                    <?php }?>
                    <th><?php echo $_smarty_tpl->tpl_vars['tableHeadMethodTitle']->value;?>
</th>
                    <th><?php echo $_smarty_tpl->tpl_vars['tableHeadDescriptionTitle']->value;?>
</th>
                </tr>
            </thead>
            <tbody>
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['objects']->value, 'object');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['object']->value) {
?> <tr id='<?php echo $_smarty_tpl->tpl_vars['object']->value['id'];?>
' class='<?php echo $_smarty_tpl->tpl_vars['object']->value['class'];?>
'><td name='icon' class='shrink'><a href='<?php echo $_smarty_tpl->tpl_vars['object']->value['url'];?>
'><img class='media-object' src=<?php echo $_smarty_tpl->tpl_vars['object']->value['icon'];?>
></a></td><td name='title' class='shrink'><a href='<?php echo $_smarty_tpl->tpl_vars['object']->value['url'];?>
'><?php echo $_smarty_tpl->tpl_vars['object']->value['name'];?>
</a></td><td name='patch' class='shrink'><?php echo $_smarty_tpl->tpl_vars['object']->value['patch'];?>
</td><?php if ('tableHeadCanFlyTitle' == '') {?><td class='shrink'><?php echo $_smarty_tpl->tpl_vars['object']->value['canFly'];?>
</td><?php }?><td class='shrink'><?php echo $_smarty_tpl->tpl_vars['object']->value['method'];?>
</td><td class='expand'><?php echo $_smarty_tpl->tpl_vars['object']->value['methodDesc'];?>
</td></tr> <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>


            </tbody>
        </table>
    </div>
</div><?php }
}
