<?php

/*
 * This file is part of RazyFramework.
 *
 * (c) Ray Fung <hello@rayfung.hk>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace RazyFramework
{
  return function ($module, $categoryName, $moduleName, $modulePermission) {
  	if (!isset($this->permissions[$module->getCode()])) {
  		$this->permissions[$module->getCode()] = [
  			'module_name'   => $moduleName,
  			'category_name' => $categoryName,
  			'permission'    => [],
  		];
  	}

  	if (is_array($modulePermission)) {
  		foreach ($modulePermission as $method => $name) {
  			$this->permissions[$module->getCode()]['permission'][$method] = $name;
  		}
  	}
  };
}
