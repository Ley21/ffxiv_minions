<table class='table table-condensed'>
    <thead>
        <tr>
            <th>{$tableHeaderNr}</th>
            <th>{$tableHeaderName}</th>
            <th>{$tableHeaderWorld}</th>
            {if $type eq "minions"}
            <th>{$tableHeaderCountMinions}</th>
            {elseif $type eq "mounts"}
            <th>{$tableHeaderCountMounts}</th>
            {else}
            <th>{$tableHeaderCountMinions}</th>
            <th>{$tableHeaderCountMounts}</th>
            <th>{$tableHeaderCountAll}</th>
            {/if}
            <th>{$tableHeaderLastSync}</th>
        </tr>
    </thead>
    <tbody>
        {foreach $players as $player}
        <tr class='active' id='{$player.id}'>
            <td>{$player.nr}</td>
            <td><a onclick='loadCharakter({$player.id})'>{$player.name}</a></td>
            <td>{$player.world}</td>
            {if $type eq "minions"}
            <td>{$player.minions}</td>
            {elseif $type eq "mounts"}
            <td>{$player.mounts}</td>
            {else}
            <td>{$player.minions}</td>
            <td>{$player.mounts}</td>
            <td>{$player.all}</td>
            {/if}
            <td>
                {if $player.old}
                <button class='btn btn-info' onclick='updateCharakter({$player.id})'>{$syncBtnText}</button> {else} {$player.sync} {/if}
            </td>
        </tr>
        {/foreach}

    </tbody>
</table>