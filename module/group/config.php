<?php

/*
 * This file is part of RazyFramework.
 *
 * (c) Ray Fung <hello@rayfung.hk>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

return [
	'module_code' => 'group',
	'author'      => 'Ray Fung',
	'version'     => '1.0.0',
	'route'       => [
		'/'       => 'group.index',
		'install' => 'group.install',
		'create'  => 'group.process',
		'edit'    => 'group.process',
		'delete'  => 'group.delete',
	],
	'require' => [
    'core' => '^1.0.0',
		'user' => '^1.0.0',
	],
	'callable' => [
		'register' => 'group.register',
		'auth'     => 'group.auth',
	],
];
