
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

<section class="gutters">
  <div class="grid-header">
    Article List
  </div>
  <table class="panel">
    <thead>
      <tr>
        <th width="50">ID</th>
        <th>Subject</th>
        <th width="120">Post Date</th>
        <th width="50"></th>
      </tr>
    </thead>
    <!-- START BLOCK: record -->
    <tr>
      <td>{$article_id}</td>
      <td>{$subject}</td>
      <td>{$post_date}</td>
      <td>
        <div class="flex f-fill f-nowrap btn-group thin">
          <a href="{$module_root_url}edit/{$article_id}"><span class="fo-pencil"></span>Edit</a>
          <a href="{$module_root_url}delete/{$article_id}"><span class="fo-cross"></span>Delete</a>
        </div>
      </td>
    </tr>
    <!-- END BLOCK: record -->
  </table>
</section>
<section class="gutters">
{$pagination}
</section>
