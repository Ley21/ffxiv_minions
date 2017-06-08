<?php
/* Smarty version 3.1.30, created on 2017-05-09 08:59:41
  from "/home/ubuntu/workspace/template/character.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_591184fdf14488_72716727',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '757a376e50b270fd9df0157418a8da6bfe791e1a' => 
    array (
      0 => '/home/ubuntu/workspace/template/character.tpl',
      1 => 1494319479,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_591184fdf14488_72716727 (Smarty_Internal_Template $_smarty_tpl) {
?>
<center>

    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4><b><?php echo $_smarty_tpl->tpl_vars['title']->value['character'];?>
</b></h4>
                </div>
                <div class="panel-body">
                    <div id="<?php echo $_smarty_tpl->tpl_vars['player']->value['id'];?>
" class="player_id"></div><img src=<?php echo $_smarty_tpl->tpl_vars['player']->value['img'];?>
 class="img-rounded img-responsive"></div>
                <div class="row">
                    <div class="col-xs-1 col-sm-1"></div>
                    <div class="col-xs-7 col-sm-5 info_grid"><b><?php echo $_smarty_tpl->tpl_vars['title']->value['name'];?>
</b></div>
                    <div class="col-xs-3 col-sm-5 info_grid_text"><a href="<?php echo $_smarty_tpl->tpl_vars['player']->value['lodestone'];?>
" target="_blank" id="p_name"><?php echo $_smarty_tpl->tpl_vars['player']->value['name'];?>
</a></div>
                    <div class="col-xs-1 col-sm-1"></div>
                </div>
                <div class="row">
                    <div class="col-xs-1 col-sm-1"></div>
                    <div class="col-xs-7 col-sm-5 info_grid"><b><?php echo $_smarty_tpl->tpl_vars['title']->value['world'];?>
</b></div>
                    <div class="col-xs-3 col-sm-5 info_grid_text"><?php echo $_smarty_tpl->tpl_vars['player']->value['world'];?>
</div>
                    <div class="col-xs-1 col-sm-1"></div>
                </div>
                <?php if ($_smarty_tpl->tpl_vars['player']->value['title'] != '') {?>
                <div class="row">
                    <div class="col-xs-1 col-sm-1"></div>
                    <div class="col-xs-7 col-sm-5 info_grid"><b><?php echo $_smarty_tpl->tpl_vars['title']->value['title'];?>
<br></b></div>
                    <div class="col-xs-3 col-sm-5 info_grid_text"><?php echo $_smarty_tpl->tpl_vars['player']->value['title'];?>
</div>
                    <div class="col-xs-1 col-sm-1"></div>
                </div>
                <?php }?>
                <div class="row">
                    <div class="col-xs-1 col-sm-1"></div>
                    <div class="col-xs-7 col-sm-5 info_grid"><b><?php echo $_smarty_tpl->tpl_vars['title']->value['race'];?>
</b></div>
                    <div class="col-xs-3 col-sm-5 info_grid_text"><?php echo $_smarty_tpl->tpl_vars['player']->value['race'];?>
</div>
                    <div class="col-xs-1 col-sm-1"></div>
                </div>
                <div class="row">
                    <div class="col-xs-1 col-sm-1"></div>
                    <div class="col-xs-7 col-sm-5 info_grid"><b><?php echo $_smarty_tpl->tpl_vars['title']->value['gender'];?>
</b></div>
                    <div class="col-xs-3 col-sm-5 info_grid_text"><?php echo $_smarty_tpl->tpl_vars['player']->value['gender'];?>
</div>
                    <div class="col-xs-1 col-sm-1"></div>
                </div>
                <div class="row">
                    <div class="col-xs-1 col-sm-1"></div>
                    <div class="col-xs-7 col-sm-5 info_grid"><b><?php echo $_smarty_tpl->tpl_vars['title']->value['gc'];?>
</b></div>
                    <div class="col-xs-3 col-sm-5 info_grid_text"><?php echo $_smarty_tpl->tpl_vars['player']->value['gc'];?>
</div>
                    <div class="col-xs-1 col-sm-1"></div>
                </div>
                <div class="row">
                    <div class="col-xs-1 col-sm-1"></div>
                    <div class="col-xs-7 col-sm-5 info_grid"><b><?php echo $_smarty_tpl->tpl_vars['title']->value['fc'];?>
</b></div>
                    <div class="col-xs-3 col-sm-5 info_grid_text"><a id="<?php echo $_smarty_tpl->tpl_vars['player']->value['fc']['id'];?>
" class="freeCompany"><?php echo $_smarty_tpl->tpl_vars['player']->value['fc']['name'];?>
</a></div>
                    <div class="col-xs-1 col-sm-1"></div>
                </div>
                <div class="row">
                    <div class="col-xs-1 col-sm-1"></div>
                    <div class="col-xs-7 col-sm-5 info_grid"><b><br><?php echo $_smarty_tpl->tpl_vars['title']->value['rank']['global'];?>
<br><br></b></div>
                    <div class="col-xs-3 col-sm-5 info_grid_text"><b><?php echo $_smarty_tpl->tpl_vars['title']->value['rank']['all'];?>
:</b> <?php echo $_smarty_tpl->tpl_vars['player']->value['rank']['global']['all'];?>
<br><b><?php echo $_smarty_tpl->tpl_vars['title']->value['rank']['minions'];?>
:</b> <?php echo $_smarty_tpl->tpl_vars['player']->value['rank']['global']['minions'];?>
<br><b><?php echo $_smarty_tpl->tpl_vars['title']->value['rank']['mounts'];?>
:</b> <?php echo $_smarty_tpl->tpl_vars['player']->value['rank']['global']['mounts'];?>
</div>
                    <div class="col-xs-1 col-sm-1"></div>
                </div>
                <div class="row">
                    <div class="col-xs-1 col-sm-1"></div>
                    <div class="col-xs-7 col-sm-5 info_grid"><b><br><?php echo $_smarty_tpl->tpl_vars['title']->value['rank']['world'];?>
<br><br></b></div>
                    <div class="col-xs-3 col-sm-5 info_grid_text"><b><?php echo $_smarty_tpl->tpl_vars['title']->value['rank']['all'];?>
:</b> <?php echo $_smarty_tpl->tpl_vars['player']->value['rank']['world']['all'];?>
<br><b><?php echo $_smarty_tpl->tpl_vars['title']->value['rank']['minions'];?>
:</b> <?php echo $_smarty_tpl->tpl_vars['player']->value['rank']['world']['minions'];?>
<br><b><?php echo $_smarty_tpl->tpl_vars['title']->value['rank']['mounts'];?>
:</b> <?php echo $_smarty_tpl->tpl_vars['player']->value['rank']['world']['mounts'];?>
</div>
                    <div class="col-xs-1 col-sm-1"></div>
                </div>
                <div class="row">
                    <div class="col-xs-1 col-sm-1"></div>
                    <div class="col-xs-7 col-sm-5 info_grid"><b><?php echo $_smarty_tpl->tpl_vars['title']->value['sync'];?>
</b></div>
                    <div class="col-xs-3 col-sm-5 info_grid_text"><?php echo $_smarty_tpl->tpl_vars['player']->value['sync'];?>
</div>
                    <div class="col-xs-1 col-sm-1"></div>
                </div>
                <div class="row"><button type="button" class="btn btn-success" id="char_button" style="width:83%"></button></div><br></div>
        </div>
        <div class="col-md-8">
            <div class="panel panel-primary">
                <div class="panel-heading" data-toggle="collapse" data-target="#div_0" aria-expanded="true" aria-controls="div_0">
                    <h4><b><?php echo $_smarty_tpl->tpl_vars['title']->value['owned']['minions'];?>
: <?php echo $_smarty_tpl->tpl_vars['player']->value['minions_count'];?>
</b></h4>
                </div>
                <div class="panel-body">
                    <div class="collapse  in" id="div_0">
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['player']->value['minions'], 'minion');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['minion']->value) {
?>
                        <div class="col-xs-0 col-md-2" style="width:auto; padding:0px">
                            <a id="minion_<?php echo $_smarty_tpl->tpl_vars['minion']->value['id'];?>
" href="<?php echo $_smarty_tpl->tpl_vars['minion']->value['xivdb'];?>
" class="thumbnail" data-xivdb-key="xivdb_minion_<?php echo $_smarty_tpl->tpl_vars['minion']->value['id'];?>
"><img class="media-object" alt="<?php echo $_smarty_tpl->tpl_vars['minion']->value['name'];?>
" src=<?php echo $_smarty_tpl->tpl_vars['minion']->value['icon'];?>
></a>
                        </div>
                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                        
                    </div>
                </div>
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading" data-toggle="collapse" data-target="#div_1" aria-expanded="true" aria-controls="div_1">
                    <h4><b><?php echo $_smarty_tpl->tpl_vars['title']->value['owned']['mounts'];?>
: <?php echo $_smarty_tpl->tpl_vars['player']->value['mounts_count'];?>
</b></h4>
                </div>
                <div class="panel-body">
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['player']->value['mounts'], 'mount');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['mount']->value) {
?>
                        <div class="col-xs-0 col-md-2" style="width:auto; padding:0px">
                            <a id="mount_<?php echo $_smarty_tpl->tpl_vars['mount']->value['id'];?>
" href="<?php echo $_smarty_tpl->tpl_vars['mount']->value['xivdb'];?>
" class="thumbnail" data-xivdb-key="xivdb_mount_<?php echo $_smarty_tpl->tpl_vars['mount']->value['id'];?>
"><img class="media-object" alt="<?php echo $_smarty_tpl->tpl_vars['mount']->value['name'];?>
" src=<?php echo $_smarty_tpl->tpl_vars['mount']->value['icon'];?>
></a>
                        </div>
                    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                </div>
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading" data-toggle="collapse" data-target="#div_2" aria-expanded="true" aria-controls="div_2">
                    <h4><b><?php echo $_smarty_tpl->tpl_vars['title']->value['rarest'];?>
</b></h4>
                </div>
                <div class="panel-body">
                    <div class="collapse  in" id="div_2">
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['player']->value['rarest'], 'elems', false, 'type');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['type']->value => $_smarty_tpl->tpl_vars['elems']->value) {
?>
                        <div class="media">
                            <div class="col-xs-0 col-md-2" style="width:auto; padding:0px">
                                <a id="<?php echo $_smarty_tpl->tpl_vars['type']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['elems']->value['id'];?>
" href="<?php echo $_smarty_tpl->tpl_vars['elems']->value['xivdb'];?>
" class="thumbnail" data-xivdb-key="xivdb_<?php echo $_smarty_tpl->tpl_vars['type']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['elems']->value['id'];?>
"><img class="media-object" alt="<?php echo $_smarty_tpl->tpl_vars['elems']->value['name'];?>
" src=<?php echo $_smarty_tpl->tpl_vars['elems']->value['icon'];?>
></a>
                            </div>
                            <div class="col-xs-0 col-md-2" style="width:auto; padding:0px; padding-left:2em">
                                <h4> <?php echo $_smarty_tpl->tpl_vars['elems']->value['name'];?>
</h4>
                            </div>
                        </div>
                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                    </div>
                </div>
            </div>
        </div>
    
    </div><br>
    <?php echo $_smarty_tpl->tpl_vars['missing_minions_table']->value;?>

    <?php echo $_smarty_tpl->tpl_vars['missing_mounts_table']->value;?>

</center><?php }
}
