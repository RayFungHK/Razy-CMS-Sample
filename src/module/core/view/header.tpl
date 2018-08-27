<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,minimum-scale=1.0,initial-scale=1.0,user-scalable=yes,maximum-scale=1.0">
  <title>Razy CMS Sample</title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  <link rel="stylesheet" href="{$root_view_path}css/style.css">
  <link rel="stylesheet" href="{$root_view_path}css/flexit.css">
  <link rel="stylesheet" href="{$root_view_path}css/fontone.css">
</head>
<body>
  <div class="flex f-row">
    <topbar>
      <div class="flex f-fill">
        <div id="site-name"><a href="{$url_base}">Razy CMS Sample</a></div>
        <div id="navigator" class="col flex f-fill f-between">
          <div id="menu-icon"><a id="menu" href="javascript:void(0)"></a></div>
          <div id="logout-icon"><a href="{$url_base}/user/session?signout" class="fo-power"></a></div>
        </div>
      </div>
    </topbar>
    <main class="col flex f-fill f-nowrap">
      <sidebar>
        <div id="user-panel">
          <div id="avatar" class="flex f-center">
            <div>
              <a href="{$url_base}/user/profile"><img src="http://www.zone4homes.com/themes/fo/img/avatar.png" /></a>
            </div>
          </div>
          <div id="user-name">{$display_name}</div>
          <div id="group-name">{$group_name}</div>
        </div>
        <div id="menu" class="f-auto">
          <!-- START BLOCK: category -->
          <div class="category">{$name}</div>
            <!-- START BLOCK: menu -->
            <a href="{$path}">{$name}</a>
            <!-- END BLOCK: menu -->
          <!-- END BLOCK: category -->
        </div>
      </sidebar>
      <div class="col flex f-fill f-row">
        <div class="col">
