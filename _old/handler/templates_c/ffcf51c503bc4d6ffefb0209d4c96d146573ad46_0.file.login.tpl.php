<?php
/* Smarty version 3.1.30, created on 2017-03-03 09:35:48
  from "/home/ubuntu/workspace/template/login.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58b938f48b1837_44113467',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ffcf51c503bc4d6ffefb0209d4c96d146573ad46' => 
    array (
      0 => '/home/ubuntu/workspace/template/login.tpl',
      1 => 1488533550,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58b938f48b1837_44113467 (Smarty_Internal_Template $_smarty_tpl) {
?>
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel"><?php echo $_smarty_tpl->tpl_vars['loginHeader']->value;?>
</h4>
  </div>
  <div class="modal-body">
    <form id="login_form">
      <div class="form-group">
        <label for="username"><?php echo $_smarty_tpl->tpl_vars['username']->value;?>
</label>
        <input type="text" class="form-control" id="username" placeholder="<?php echo $_smarty_tpl->tpl_vars['username']->value;?>
">
      </div>
      <div class="form-group">
        <label for="password"><?php echo $_smarty_tpl->tpl_vars['password']->value;?>
</label>
        <input type="password" class="form-control" id="password" placeholder="<?php echo $_smarty_tpl->tpl_vars['password']->value;?>
">
      </div>
      
    </form>
    <center><p class="bg-danger"><b><?php echo $_smarty_tpl->tpl_vars['warning']->value;?>
</b></p></center>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $_smarty_tpl->tpl_vars['cancel']->value;?>
</button>
    <button type="button" class="btn btn-primary" id="login"><?php echo $_smarty_tpl->tpl_vars['login']->value;?>
</button>
  </div>
<?php }
}
