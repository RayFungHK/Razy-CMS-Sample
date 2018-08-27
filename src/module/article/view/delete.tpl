
<section class="navigator">
  <div class="flex f-middle">
    <div class="col section-header">
      <div>Article Management</div>
      <div>Manage your markdown article</div>
    </div>
    <div>
      <div class="flex f-fill btn-group">
        <a href="{$module_root}create"><span class="fo-plus"></span>New Group</a>
      </div>
    </div>
  </div>
</section>

<section class="gutters">
  <div class="panel">
    <div class="panel-title">{$title}</div>
    <div class="confirm-message">{$message}</div>
    <div class="confirm-message">
      <div class="btn-group flex f-fill">
        <a href="{$confirm_url}" class="confirm-red">{$confirm_text}</a>
        <a href="{$cancel_url}">Cancel</a>
      </div>
    </div>
  </div>
</section>
