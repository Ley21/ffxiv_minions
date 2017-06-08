<?php
/* Smarty version 3.1.30, created on 2017-01-17 20:10:58
  from "/home/ubuntu/workspace/template/admin.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_587e7a529c6403_26309308',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '56bbe73c25ebe7945b37820b1bf15a59858b1bf0' => 
    array (
      0 => '/home/ubuntu/workspace/template/admin.tpl',
      1 => 1483640568,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_587e7a529c6403_26309308 (Smarty_Internal_Template $_smarty_tpl) {
?>
<form>
    <div class="form-group">

        <div class="row">
            <div class="col-md-3" />
            <div class="col-md-3">
                <label><?php echo $_smarty_tpl->tpl_vars['title']->value['last_minion_id'];?>
</label>
                <p class="form-control-static" id="minion_id"><?php echo $_smarty_tpl->tpl_vars['last_minion_id']->value;?>
</p>
            </div>
            <div class="col-md-3"> <label><?php echo $_smarty_tpl->tpl_vars['title']->value['last_mount_id'];?>
</label>
                <p class="form-control-static" id="mount_id"><?php echo $_smarty_tpl->tpl_vars['last_mount_id']->value;?>
</p>
            </div>
            <div class="col-md-3" />
            </center>

        </div>
        <div class="form-group">
            <label for="key"><?php echo $_smarty_tpl->tpl_vars['title']->value['key'];?>
</label>
            <input type="password" class="form-control" id="key" placeholder="<?php echo $_smarty_tpl->tpl_vars['title']->value['key'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
">
        </div>

        <div class="form-group">
            <label class="checkbox-inline">
      <input type="checkbox" id="update" <?php if ($_smarty_tpl->tpl_vars['update']->value == true) {?>checked<?php }?>><?php echo $_smarty_tpl->tpl_vars['title']->value['update'];?>

    </label>
            <label class="checkbox-inline">
      <input type="checkbox" id="method_update" <?php if ($_smarty_tpl->tpl_vars['methodes']->value == true) {?>checked<?php }?>><?php echo $_smarty_tpl->tpl_vars['title']->value['methodes'];?>

    </label>
            <label class="checkbox-inline">
      <input type="checkbox" id="readonly" <?php if ($_smarty_tpl->tpl_vars['readonly']->value == true) {?>checked<?php }?>><?php echo $_smarty_tpl->tpl_vars['title']->value['readonly'];?>

    </label>
        </div>
</form><?php }
}
