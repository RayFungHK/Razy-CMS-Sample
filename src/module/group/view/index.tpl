
<section class="navigator">
  <div class="flex f-middle">
    <div class="col section-header">
      <div>Permission Group Management</div>
      <div>Manage group permission</div>
    </div>
    <div>
      <div class="flex f-fill btn-group">
        <a href="{$module_root_url}create"><span class="fo-plus"></span>New Group</a>
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
        <th width="80">Group ID</th>
        <th>Group Name</th>
        <th width="50"></th>
      </tr>
    </thead>
    <!-- START BLOCK: record -->
    <tr>
      <td>{$group_id}</td>
      <td>{$name}</td>
      <td>
        <div class="flex f-fill f-nowrap btn-group thin">
          <a href="{$module_root_url}edit/{$group_id}"><span class="fo-pencil"></span>Edit</a>
          <a href="{$module_root_url}delete/{$group_id}"><span class="fo-cross"></span>Delete</a>
        </div>
      </td>
    </tr>
    <!-- END BLOCK: record -->
  </table>
</section>
<section class="gutters">
{$pagination}
</section>
