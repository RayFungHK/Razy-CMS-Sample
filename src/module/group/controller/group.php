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
  class group extends IController
  {
  	protected $permissions = [];

  	protected function __onModuleLoaded()
  	{
  		return true;
  	}

  	public function __onReady()
  	{
  		// If database is not connected
  		if (!($dba = $this->load->db('local'))) {
  			return false;
  		}

  		// Razy will not start routing before Ready Stage, so you should use
  		// getRoute() to get the estimated route
  		$route = $this->module->getRoute();

  		// If we are not in installation stage
  		if (!defined('INSTALLATION_STAGE')) {
  			$config = $this->load->config('install');
  			// If install.php config file does not exist, redirect to installation page
  			if (!$config->isLoaded()) {
  				if ('install' !== $route) {
  					$this->manager->locate($this->module->getRemapPath() . 'install');
  				} else {
  					// The request url has routed to installation page
  					// Declare a constant to determine current is the installation stage
  					define('INSTALLATION_STAGE', true);
  				}
  			}
  		}

  		return true;
  	}

  	public function __onPrepareRouting()
  	{
  		// Add menu
  		$this->manager->execute('core.setMenu', 'Users & Roles', 'Permission Group', $this->module);

  		// Register Permission
  		$this->manager->execute('group.register', $this->module, 'Users & Roles', 'Permission Group', [
  			'view'   => 'View',
  			'create' => 'Create',
  			'edit'   => 'Edit',
  			'delete' => 'Delete',
  		]);

  		return true;
  	}

  	public function __onBeforeRoute()
  	{
  		$route = $this->module->getRouteName();
  		// Check view permission
  		if ('install' === $route) {
  			return true;
  		}

  		// Check view permission
  		if (!$this->manager->execute('group.auth', $this->module, 'view')) {
  			return true;
  		}
  	}

  	public function index()
  	{
  		// Check user login
  		$this->manager->execute('user.checkUserLogin');

  		// Load header
  		$this->manager->execute('core.loadHeader');

  		// Load `index.tpl` template file
  		$tplManager  = $this->load->view('index')->addToQueue('body');
  		$root        = $tplManager->getRootBlock();
  		$dba         = $this->load->db('local');
  		$userdata    = $this->manager->execute('user.getUser');
  		$page        = (isset($_GET['page'])) ? intval($_GET['page']) : 0;
  		$startRecord = ($page > 1) ? ($page - 1) * 20 : 0;

  		$query = $dba->select('cms_group')->where('!disabled')->limit($startRecord, 20)->query();
  		while ($result = $query->fetch()) {
  			$root->newBlock('record')->assign([
  				'group_id' => $result['group_id'],
  				'name'     => $result['name'],
  			]);
  		}

  		$totalRows  = $dba->select('cms_group', 'COUNT(*) as total')->where('!disabled')->lazy();
  		$pagination = new Pagination(['item_per_page' => 20]);
  		$root->assign([
  			'pagination' => $pagination->parse($totalRows['total']),
  		]);

  		$this->manager->execute('core.loadFooter');
  	}
  }
}
