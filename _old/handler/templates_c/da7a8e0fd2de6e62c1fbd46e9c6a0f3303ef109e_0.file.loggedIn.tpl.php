<?php
/* Smarty version 3.1.30, created on 2017-03-03 09:35:57
  from "/home/ubuntu/workspace/template/loggedIn.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58b938fde99452_58396404',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'da7a8e0fd2de6e62c1fbd46e9c6a0f3303ef109e' => 
    array (
      0 => '/home/ubuntu/workspace/template/loggedIn.tpl',
      1 => 1488533550,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58b938fde99452_58396404 (Smarty_Internal_Template $_smarty_tpl) {
?>

  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel"><?php echo $_smarty_tpl->tpl_vars['loginHeader']->value;?>
</h4>
  </div>
  <div class="modal-body">
    <center><p class="bg-success"><b><?php echo $_smarty_tpl->tpl_vars['success']->value;?>
</b></p></center>
  </div>
  <div class="modal-footer">
  </div>

<?php }
}
