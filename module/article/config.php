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
	'module_code' => 'article',
	'author'      => 'Ray Fung',
	'version'     => '1.0.0',
	'route'       => [
		'/'       => 'article.index',
		'install' => 'article.install',
		'create'  => 'article.process',
		'edit'    => 'article.process',
		'delete'  => 'article.delete',
	],
	'require' => [
    'core' => '^1.0.0',
		'user' => '^1.0.0',
	],
];
