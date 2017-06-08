<div class="form-group">
    <label for="type">{$title.type}</label>
    <select class="form-control" id="type">
        <option value="">----------</option>
        <option value="minions" {if $type == "minions"}selected{/if}>{$title.minions}</option>
        <option value="mounts" {if $type == "mounts"}selected{/if}>{$title.mounts}</option>
        <option value="question" {if $type == "question"}selected{/if}>{$title.question}</option>
    </select>
    {if $type == "minions"}
    <label for="id">{$title.selectName}</label>
    <select class="form-control" id="id">
        <option value="">----------</option>
        {foreach $minions as $minion}
            <option value="{$minion.id}" {if $id == {$minion.id}}selected{/if}>{$minion.name}</option>
        {/foreach}
    </select>
    {elseif $type == "mounts"}
    <label for="id">{$title.selectName}</label>
    <select class="form-control" id="id">
        <option value="">----------</option>
        {foreach $mounts as $mount}
            <option value="{$mount.id}" {if $id == {$mount.id}}selected{/if}>{$mount.name}</option>
        {/foreach}
    </select>
    {elseif $type == "question"}
        <label for='question'>{$title.question}(en/de):</label>
        <textarea class='form-control' id='question' rows='4' cols='50'></textarea>
    {/if}
    {if $id != ""}
    <label for="method">{$title.existingMethod}</label>
    <select class="form-control" id="method">
        <option value="">----------</option>
        {foreach $methodes.exist as $meth}
            <option value="{$meth.en}" {if $method.name == $meth.en}selected{/if}>{$meth.name}</option>
        {/foreach}
    </select>
    <label for="new_method">{$title.newMethod}</label>
    <select class="form-control" id="new_method">
        <option value="">----------</option>
        {foreach $methodes.new as $meth}
            <option value="{$meth.en}" {if $method.name == $meth.en}selected{/if}>{$meth.name}</option>
        {/foreach}
    </select>
    {/if}
    {if $method.name != ""}
        <label for='method_description_en'>{$title.desc}[en]:</label>
        <textarea class='form-control' id='method_description_en' rows='4' cols='50'>{$method.desc_en}</textarea>
        <label for='method_description_fr'>{$title.desc}[fr]:</label>
        <textarea class='form-control' id='method_description_fr' rows='4' cols='50'>{$method.desc_fr}</textarea>
        <label for='method_description_de'>{$title.desc}[de]:</label>
        <textarea class='form-control' id='method_description_de' rows='4' cols='50'>{$method.desc_de}</textarea>
        <label for='method_description_ja'>{$title.desc}[ja]:</label>
        <textarea class='form-control' id='method_description_ja' rows='4' cols='50'>{$method.desc_ja}</textarea>
    {/if}
    
</div>