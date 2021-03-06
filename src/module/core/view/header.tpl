<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,minimum-scale=1.0,initial-scale=1.0,user-scalable=yes,maximum-scale=1.0">
  <title>Razy CMS Sample</title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  <link rel="stylesheet" href="{$shared_view_url}css/flexit.css">
  <link rel="stylesheet" href="{$shared_view_url}css/style.css">
  <link rel="stylesheet" href="{$shared_view_url}css/fontone.css">
  <script type="text/javascript" src="{$shared_view_url}/js/Void0.js"></script>
  <script type="text/javascript" src="{$shared_view_url}/js/common.js"></script>

</head>
<body>
  <div class="flex f-row">
    <topbar>
      <div class="flex f-fill">
        <div id="site-name"><a href="{$system_root_url}">Razy CMS Sample</a></div>
        <div id="navigator" class="col flex f-fill f-between">
          <div id="menu-icon"><a id="menu" href="javascript:void(0)"></a></div>
          <div id="logout-icon"><a href="{$system_root_url}/user/session?signout" class="fo-power"></a></div>
        </div>
      </div>
    </topbar>
    <main class="col flex f-fill f-nowrap">
      <sidebar>
        <div id="user-panel">
          <div id="avatar" class="flex f-center">
            <div>
              <img src="{$shared_view_url}images/avatar.png" />
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
      <div id="main-body" class="col flex f-fill f-row">
        <div class="col">
