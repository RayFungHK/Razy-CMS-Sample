<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, minimal-ui" />
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="{$shared_view_url}css/flexit.css" />
  <link rel="stylesheet" type="text/css" href="{$shared_view_url}css/install.css" />
  <link rel="stylesheet" type="text/css" href="{$shared_view_url}css/fontone.css" />
  <script type="text/javascript" src="{$shared_view_url}js/Void0.js"></script>
  <title>Setup database connection</title>
</head>
<body>
  <div class="flex f-center f-middle">
    <div class="install-panel">
      <div class="module-detail flex f-gutters">
        <div><span>{$module_code}</span></div>
        <div><span>{$version}</span></div>
      </div>
      <div class="title">Installation</div>
      <div class="description">Setup database connection</div>
      <!-- START BLOCK: success -->
      <div class="success">
        <div><i class="fo-tick"></i></div>
        Configuration Completed
        <div><a href="{$system_root_url}" class="conitune">Contiune</a></div>
      </div>
      <!-- END BLOCK: success -->
      <!-- START BLOCK: setting -->
      <!-- START BLOCK: failed -->
      <div class="error">
        {$error}
      </div>
      <!-- END BLOCK: failed -->
      <div class="content">
        <form action="{$module_url}" method="post">
          <div class="parameter">Host</div>
          <div><input type="text" name="host" value="{$val_host}"{$err_host} checked="checked"{/$err_host} /></div>
          <div class="parameter">Username</div>
          <div><input type="text" name="username" value="{$val_username}" /></div>
          <div class="parameter">Password</div>
          <div><input type="password" name="password" /></div>
          <div class="parameter">Database</div>
          <div><input type="text" name="database" value="{$val_database}" /></div>
          <div><input type="submit" value="Connect"></div>
        </form>
      </div>
      <!-- END BLOCK: setting -->
    </div>
  </div>
</body>
</html>
