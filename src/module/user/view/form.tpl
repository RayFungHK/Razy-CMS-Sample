
<section class="navigator">
  <div class="flex f-middle">
    <div class="col section-header">
      <div>User Management</div>
      <div>Manage system user account and permission</div>
    </div>
    <div>
      <div class="flex f-fill btn-group">
        <a href="{$module_root_url}create"><span class="fo-plus"></span>New User</a>
      </div>
    </div>
  </div>
</section>

<form action="{$form_target}" method="post">
  <section class="gutters">
    <div class="panel">
      <div class="panel-title">User</div>
      <div class="form-set">
        <div class="form-group flex f-middle">
          <label class="col-3">Group<span>Chose a permission group</span></label>
          <div class="col">
            <div class="selectbox">
              {$selectbox_group}
            </div>
          </div>
        </div>
        <div class="form-group flex f-middle">
          <label class="col-3">Login Name<span>Allow only alphabets</span></label>
          <div class="col">
            <div class="icon-textbox flex f-fill{$err_login_name|if:" warning"}">
              <span class="fo-user"></span>
              <div class="col">
                <input type="text" name="login_name" value="{$val_login_name}" />
              </div>
            </div>
            {$err_login_name|if:"<span>{$err_login_name}</span>"}
          </div>
        </div>
        <div class="form-group flex f-middle">
          <label class="col-3">Password<span>At least 6 characters</span></label>
          <div class="col">
            <div class="icon-textbox flex f-fill{$err_password|if:" warning"}">
              <span class="fo-key"></span>
              <div class="col">
                <input type="password" name="password"{$err_password|if:" class=\"warning\""} />
              </div>
            </div>
            {$err_password|if:"<span>{$err_password}</span>"}
          </div>
        </div>
        <div class="form-group flex f-middle">
          <label class="col-3">Retype Password</label>
          <div class="col">
            <div class="icon-textbox flex f-fill">
              <span class="fo-key"></span>
              <div class="col">
                <input type="password" name="retype_password" />
              </div>
            </div>
          </div>
        </div>
        <div class="form-group flex f-middle">
          <label class="col-3">Display Name<span>The name will display in the system</span></label>
          <div class="col">
            <input type="text" name="display_name" value="{$val_display_name}"{$err_display_name|if:" class=\"warning\""} />
            {$err_display_name|if:"<span>{$err_display_name}</span>"}
          </div>
        </div>
        <div class="form-group flex f-middle">
          <label class="col-3">Email</label>
          <div class="col">
            <input type="text" name="email" value="{$val_email}"{$err_email|if:" class=\"warning\""} />
            {$err_email|if:"<span>{$err_email}</span>"}
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="gutters flex f-right">
    <input type="submit" class="submit-btn" value="Submit" />
  </section>
</form>
