<?php

/*
 * This file is part of RazyFramwork.
 *
 * (c) Ray Fung <hello@rayfung.hk>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

return [
	'module_code' => 'user',
	'author'      => 'Ray Fung',
	'version'     => '1.0.0',
	'route'       => [
		'/'       => 'user.index',
		'install' => 'user.install',
		'session' => 'user.session',
		'create'  => 'user.process',
		'edit'    => 'user.process',
		'delete'  => 'user.delete',
	],
	'require' => [
    'core' => '^1.0.0',
		'group' => '^1.0.0',
	],
	'callable' => [
		'getUser'        => 'user.getUser',
		'checkUserLogin' => 'user.checkUserLogin',
	],
];
