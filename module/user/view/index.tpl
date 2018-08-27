
<section class="navigator">
  <div class="flex f-middle">
    <div class="col section-header">
      <div>User Management</div>
      <div>Manage system user account and permission</div>
    </div>
    <div>
      <div class="flex f-fill btn-group">
        <a href="{$module_root}create"><span class="fo-plus"></span>New User</a>
      </div>
    </div>
  </div>
</section>

<section class="gutters">
  <div class="grid-header">
    User List
  </div>
  <table class="panel">
    <thead>
      <tr>
        <th width="50">ID</th>
        <th>Display Name</th>
        <th width="120">Login Name</th>
        <th width="170">Last Login</th>
        <th width="50"></th>
      </tr>
    </thead>
    <!-- START BLOCK: record -->
    <tr>
      <td>{$user_id}</td>
      <td>{$display_name}</td>
      <td>{$login_name}</td>
      <td>{$last_login}</td>
      <td>
        <div class="flex f-fill f-nowrap btn-group thin">
          <a href="{$module_root}edit/{$user_id}"><span class="fo-pencil"></span>Edit</a>
          <a href="{$module_root}delete/{$user_id}"><span class="fo-cross"></span>Delete</a>
        </div>
      </td>
    </tr>
    <!-- END BLOCK: record -->
  </table>
</section>
<section class="gutters">
{$pagination}
</section>
