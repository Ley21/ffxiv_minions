
<table class='table table-condensed' id="ranking">
    <thead>
        <tr>
            <th>{$tableHeaderNr}</th>
            <th>{$tableHeaderName}</th>
            <th>{$tableHeaderPercent}</th>
            <th>{$tableHeaderCount}</th>
        </tr>
    </thead>
    <tbody>
        {foreach $objects as $obj}
        <tr class='active' id='{$obj.type}_{$obj.id}' class="">
            <td>{$obj.nr}</td>
            <td><a href="{$obj.link}"><img src={$obj.icon}/>{$obj.name}</a></td>
            <td>{$obj.percent}</td>
            <td>{$obj.count}</td>
        </tr>
        {/foreach}

    </tbody>
</table>