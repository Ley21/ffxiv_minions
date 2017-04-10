<center>

    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4><b>{$title.character}</b></h4>
                </div>
                <div class="panel-body">
                    <div id="{$player.id}" class="player_id"></div><img src={$player.img} class="img-rounded img-responsive"></div>
                <div class="row">
                    <div class="col-xs-1 col-sm-1"></div>
                    <div class="col-xs-7 col-sm-5 info_grid"><b>{$title.name}</b></div>
                    <div class="col-xs-3 col-sm-5 info_grid_text"><a href="{$player.lodestone}" target="_blank" id="p_name">{$player.name}</a></div>
                    <div class="col-xs-1 col-sm-1"></div>
                </div>
                <div class="row">
                    <div class="col-xs-1 col-sm-1"></div>
                    <div class="col-xs-7 col-sm-5 info_grid"><b>{$title.world}</b></div>
                    <div class="col-xs-3 col-sm-5 info_grid_text">{$player.world}</div>
                    <div class="col-xs-1 col-sm-1"></div>
                </div>
                {if $player.title != ""}
                <div class="row">
                    <div class="col-xs-1 col-sm-1"></div>
                    <div class="col-xs-7 col-sm-5 info_grid"><b>{$title.title}<br></b></div>
                    <div class="col-xs-3 col-sm-5 info_grid_text">{$player.title}</div>
                    <div class="col-xs-1 col-sm-1"></div>
                </div>
                {/if}
                <div class="row">
                    <div class="col-xs-1 col-sm-1"></div>
                    <div class="col-xs-7 col-sm-5 info_grid"><b>{$title.race}</b></div>
                    <div class="col-xs-3 col-sm-5 info_grid_text">{$player.race}</div>
                    <div class="col-xs-1 col-sm-1"></div>
                </div>
                <div class="row">
                    <div class="col-xs-1 col-sm-1"></div>
                    <div class="col-xs-7 col-sm-5 info_grid"><b>{$title.gender}</b></div>
                    <div class="col-xs-3 col-sm-5 info_grid_text">{$player.gender}</div>
                    <div class="col-xs-1 col-sm-1"></div>
                </div>
                <div class="row">
                    <div class="col-xs-1 col-sm-1"></div>
                    <div class="col-xs-7 col-sm-5 info_grid"><b>{$title.gc}</b></div>
                    <div class="col-xs-3 col-sm-5 info_grid_text">{$player.gc}</div>
                    <div class="col-xs-1 col-sm-1"></div>
                </div>
                <div class="row">
                    <div class="col-xs-1 col-sm-1"></div>
                    <div class="col-xs-7 col-sm-5 info_grid"><b>{$title.fc}</b></div>
                    <div class="col-xs-3 col-sm-5 info_grid_text"><a id="{$player.fc.id}" class="freeCompany">{$player.fc.name}</a></div>
                    <div class="col-xs-1 col-sm-1"></div>
                </div>
                <div class="row">
                    <div class="col-xs-1 col-sm-1"></div>
                    <div class="col-xs-7 col-sm-5 info_grid"><b><br>{$title.rank.global}<br><br></b></div>
                    <div class="col-xs-3 col-sm-5 info_grid_text"><b>{$title.rank.all}:</b> {$player.rank.global.all}<br><b>{$title.rank.minions}:</b> {$player.rank.global.minions}<br><b>{$title.rank.mounts}:</b> {$player.rank.global.mounts}</div>
                    <div class="col-xs-1 col-sm-1"></div>
                </div>
                <div class="row">
                    <div class="col-xs-1 col-sm-1"></div>
                    <div class="col-xs-7 col-sm-5 info_grid"><b><br>{$title.rank.world}<br><br></b></div>
                    <div class="col-xs-3 col-sm-5 info_grid_text"><b>{$title.rank.all}:</b> {$player.rank.world.all}<br><b>{$title.rank.minions}:</b> {$player.rank.world.minions}<br><b>{$title.rank.mounts}:</b> {$player.rank.world.mounts}</div>
                    <div class="col-xs-1 col-sm-1"></div>
                </div>
                <div class="row">
                    <div class="col-xs-1 col-sm-1"></div>
                    <div class="col-xs-7 col-sm-5 info_grid"><b>{$title.sync}</b></div>
                    <div class="col-xs-3 col-sm-5 info_grid_text">{$player.sync}</div>
                    <div class="col-xs-1 col-sm-1"></div>
                </div>
                <div class="row"><button type="button" class="btn btn-success" id="char_button" style="width:83%"></button></div><br></div>
        </div>
        <div class="col-md-8">
            <div class="panel panel-primary">
                <div class="panel-heading" data-toggle="collapse" data-target="#div_0" aria-expanded="true" aria-controls="div_0">
                    <h4><b>{$title.owned.minions}: {$player.minions_count}</b></h4>
                </div>
                <div class="panel-body">
                    <div class="collapse  in" id="div_0">
                        {foreach $player.minions as $minion}
                        <div class="col-xs-0 col-md-2" style="width:auto; padding:0px">
                            <a id="minion_{$minion.id}" href="{$minion.xivdb}" class="thumbnail" data-xivdb-key="xivdb_minion_{$minion.id}"><img class="media-object" alt="{$minion.name}" src={$minion.icon}></a>
                        </div>
                        {/foreach}
                        
                    </div>
                </div>
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading" data-toggle="collapse" data-target="#div_1" aria-expanded="true" aria-controls="div_1">
                    <h4><b>{$title.owned.mounts}: {$player.mounts_count}</b></h4>
                </div>
                <div class="panel-body">
                    {foreach $player.mounts as $mount}
                        <div class="col-xs-0 col-md-2" style="width:auto; padding:0px">
                            <a id="mount_{$mount.id}" href="{$mount.xivdb}" class="thumbnail" data-xivdb-key="xivdb_mount_{$mount.id}"><img class="media-object" alt="{$mount.name}" src={$mount.icon}></a>
                        </div>
                    {/foreach}
                </div>
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading" data-toggle="collapse" data-target="#div_2" aria-expanded="true" aria-controls="div_2">
                    <h4><b>{$title.rarest}</b></h4>
                </div>
                <div class="panel-body">
                    <div class="collapse  in" id="div_2">
                        {foreach $player.rarest as $type=>$elems}
                        <div class="media">
                            <div class="col-xs-0 col-md-2" style="width:auto; padding:0px">
                                <a id="{$type}_{$elems.id}" href="{$elems.xivdb}" class="thumbnail" data-xivdb-key="xivdb_{$type}_{$elems.id}"><img class="media-object" alt="{$elems.name}" src={$elems.icon}></a>
                            </div>
                            <div class="col-xs-0 col-md-2" style="width:auto; padding:0px; padding-left:2em">
                                <h4> {$elems.name}</h4>
                            </div>
                        </div>
                        {/foreach}
                    </div>
                </div>
            </div>
        </div>
    
    </div><br>
    {$missing_minions_table}
    {$missing_mounts_table}
</center>