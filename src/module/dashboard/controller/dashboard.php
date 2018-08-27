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
  class dashboard extends IController
  {
  	public function index()
  	{
  		// Check user login
  		$this->manager->execute('user.checkUserLogin');

  		// Load header
  		$this->manager->execute('core.loadHeader');

      $this->load->view('index')->addToQueue('body');

  		$this->manager->execute('core.loadFooter');
  	}
  }
}
