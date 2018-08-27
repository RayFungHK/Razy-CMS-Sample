
<section class="navigator">
  <div class="flex f-middle">
    <div class="col section-header">
      <div>Permission Group Management</div>
      <div>Manage group permission</div>
    </div>
    <div>
      <div class="flex f-fill btn-group">
        <a href="{$module_root}create"><span class="fo-plus"></span>New Group</a>
      </div>
    </div>
  </div>
</section>

<form action="{$form_target}" method="post">
  <section class="gutters">
    <div class="panel">
      <div class="panel-title">Group</div>
      <div class="form-set">
        <div class="form-group flex f-middle">
          <label class="col-3">Group Name</label>
          <div class="col">
            <input type="text" name="name" value="{$val_name}" />
            {$err_name|cond:"<span>{$err_name}</span>"}
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="header">
    <span>Permission</span>
  </section>
  <!-- START BLOCK: category -->
  <section class="gutters">
    <div class="panel">
      <div class="panel-title">{$category_name}</div>
      <div class="form-set">
      <!-- START BLOCK: module -->
        <div class="form-group flex f-middle">
          <label class="col-3">{$module_name}</label>
          <div class="col checkbox-group">
            <!-- START BLOCK: option -->
            <input id="{$module_name}-{$action}" type="checkbox" name="permission[{$module_name}][{$action}]"{$checked} checked="checked"{/$checked} />
            <label for="{$module_name}-{$action}">{$name}</label>
            <!-- END BLOCK: option -->
          </div>
        </div>
      <!-- END BLOCK: module -->
      </div>
    </div>
  </section>
  <!-- END BLOCK: category -->

  <section class="gutters flex f-right">
    <input type="submit" class="submit-btn" value="Submit" />
  </section>
</form>
