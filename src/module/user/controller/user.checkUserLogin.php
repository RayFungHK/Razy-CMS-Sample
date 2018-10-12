<?php

/*
 * This file is part of RazyFramwork.
 *
 * (c) Ray Fung <hello@rayfung.hk>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace RazyFramework
{
  return function () {
    $route = $this->module->getRouteName();
    if (!USER_LOGIN && $route != 'session') {
      header('Location: ' . $this->module->getModuleRootURL() . 'session');
    }
  };
}
