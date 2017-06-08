<div class="panel panel-primary">
    <div class="panel-heading">
        <h4><b>{$tableTitle}: {$tableCount}</b></h4>
    </div>
    <div class="panel-body">
        <table class='table table-striped' id='{$tableId}'>
            <thead>
                <tr>
                    <th>{$tableHeadIconTitle}</th>
                    <th>{$tableHeadNameTitle}</th>
                    <th>{$tableHeadPatchTitle}</th>
                    {if tableHeadCanFlyTitle eq ""}
                    <th>{$tableHeadCanFlyTitle}</th>
                    {/if}
                    <th>{$tableHeadMethodTitle}</th>
                    <th>{$tableHeadDescriptionTitle}</th>
                </tr>
            </thead>
            <tbody>
                {foreach $objects as $object} {strip}
                <tr id='{$object.id}' class='{$object.class}'>
                    <td name='icon' class='shrink'>
                        <a href='{$object.url}'><img class='media-object' src={$object.icon}></a>
                    </td>
                    <td name='title' class='shrink'><a href='{$object.url}'>{$object.name}</a></td>
                    <td name='patch' class='shrink'>{$object.patch}</td>
                    {if tableHeadCanFlyTitle eq ""}
                    <td class='shrink'>{$object.canFly}</td>
                    {/if}
                    <td class='shrink'>{$object.method}</td>
                    <td class='expand'>{$object.methodDesc}</td>
                </tr>
                {/strip} {/foreach}

            </tbody>
        </table>
    </div>
</div>