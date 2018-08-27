<?php

/*
 * This file is part of RazyFramework.
 *
 * (c) Ray Fung <hello@rayfung.hk>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace RazyFramework {
  return function ($module, $action = '', $halt = true) {
  	$userdata = $this->manager->execute('user.getUser');

  	if ($action && !isset($userdata['permission'][$module->getCode()][$action]) && -1 != $userdata['group_id']) {
  		if ($halt) {
  			$this->manager->execute('core.halt', 'Permission Denied', 'You have no right to access this page.', [
  				URL_ROOT => 'Back to Dashboard',
  			]);
  		}

  		return false;
  	}

  	return true;
  };
}
