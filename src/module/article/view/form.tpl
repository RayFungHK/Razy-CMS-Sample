
<section class="navigator">
  <div class="flex f-middle">
    <div class="col section-header">
      <div>Article Management</div>
      <div>Manage your markdown article</div>
    </div>
    <div>
      <div class="flex f-fill btn-group">
        <a href="{$module_root_url}create"><span class="fo-plus"></span>New Article</a>
      </div>
    </div>
  </div>
</section>

<form action="{$form_target}" method="post">
  <section class="gutters">
    <div class="panel">
      <div class="panel-title">Article</div>
      <div class="form-set">
        <div class="form-group flex f-middle">
          <label class="col-3">Subject<span>Required</span></label>
          <div class="col">
            <input type="text" name="subject" value="{$val_subject}" />
            {$err_subject|cond:"<span>{$err_subject}</span>"}
          </div>
        </div>
        <div class="form-group flex f-middle">
          <label class="col-3">Content<span>Support Markdown syntax</span></label>
          <div class="col">
            <textarea name="content">{$val_content}</textarea>
            {$err_content|cond:"<span>{$err_content}</span>"}
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="gutters flex f-right">
    <input type="submit" class="submit-btn" value="Submit" />
  </section>
</form>
