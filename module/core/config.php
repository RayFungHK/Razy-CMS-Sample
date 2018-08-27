<?php
return [
  'module_code' => 'core',
  'author' => 'Ray Fung',
  'version' => '1.0.0',
  'callable' => array(
    'loadHeader' => 'core.loadHeader',
    'loadFooter' => 'core.loadFooter',
    'setMenu' => 'core.setMenu',
    'halt' => 'core.halt',
  ),
  'route' => [
    'install' => 'core.install'
  ]
];
?>
