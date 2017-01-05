<form>
    <div class="form-group">

        <div class="row">
            <div class="col-md-3" />
            <div class="col-md-3">
                <label>{$title.last_minion_id}</label>
                <p class="form-control-static" id="minion_id">{$last_minion_id}</p>
            </div>
            <div class="col-md-3"> <label>{$title.last_mount_id}</label>
                <p class="form-control-static" id="mount_id">{$last_mount_id}</p>
            </div>
            <div class="col-md-3" />
            </center>

        </div>
        <div class="form-group">
            <label for="key">{$title.key}</label>
            <input type="password" class="form-control" id="key" placeholder="{$title.key}" value="{$key}">
        </div>

        <div class="form-group">
            <label class="checkbox-inline">
      <input type="checkbox" id="update" {if $update == true}checked{/if}>{$title.update}
    </label>
            <label class="checkbox-inline">
      <input type="checkbox" id="method_update" {if $methodes == true}checked{/if}>{$title.methodes}
    </label>
            <label class="checkbox-inline">
      <input type="checkbox" id="readonly" {if $readonly == true}checked{/if}>{$title.readonly}
    </label>
        </div>
</form>