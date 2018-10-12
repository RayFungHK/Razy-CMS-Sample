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
  class user extends IController
  {
  	protected $userdata;

  	public function __onReady()
  	{
  		// If database is not connected
  		if (!($dba = $this->load->db('local'))) {
  			return false;
  		}

  		// Razy will not start routing before Ready Stage, so you should use
  		// getRoute() to get the estimated route
  		$route  = $this->module->getRoute();
  		$config = $this->load->config('install');

  		// If we are not in installation stage
  		if (!defined('INSTALLATION_STAGE')) {
  			// If install.php config file does not exist, redirect to installation page
  			if (!$config->isLoaded()) {
  				if ('install' !== $route) {
  					$this->manager->locate($this->module->getModuleRootURL() . 'install');
  				} else {
  					// The request url has routed to installation page
  					// Declare a constant to determine current is the installation stage
  					define('INSTALLATION_STAGE', true);
  				}
  			}
  		}

  		if ($config->isLoaded()) {
  			// Get user data
  			if (isset($_COOKIE['login']) || isset($_SESSION['login'])) {
  				$login_data = (isset($_SESSION['login'])) ? $_SESSION['login'] : json_decode($_COOKIE['login'], true);

  				$this->userdata = $dba->select('u.cms_user<g.cms_group[group_id]', 'u.*, g.permission, g.name as group_name')->where('u.login_name=?,u.password=?,u.session_key=?,!u.disabled')->lazy([
  					'login_name'  => $login_data['login_name'],
  					'password'    => $login_data['password'],
  					'session_key' => $login_data['session_key'],
  				]);

  				if ($this->userdata) {
  					$this->userdata['permission'] = ($this->userdata['permission']) ? json_decode($this->userdata['permission'], true) : [];
  					if (-1 == $this->userdata['group_id']) {
  						$this->userdata['group_name'] = 'Super User';
  					}
  				}
  			}

  			define('USER_LOGIN', (bool) $this->userdata);
  		}

  		return true;
  	}

  	public function __onPrepareRouting()
  	{
  		// Add menu
  		$this->manager->execute('core.setMenu', 'Users & Roles', 'Users', $this->module);

  		// Register Permission
  		$this->manager->execute('group.register', $this->module, 'Users & Roles', 'Users', [
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
  		if ('session' === $route || 'install' === $route) {
  			return true;
  		}

  		if (!$this->manager->execute('group.auth', $this->module, 'view')) {
  			return false;
  		}
  	}

  	public function getUser()
  	{
  		return $this->userdata;
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
  		$page        = (isset($_GET['page'])) ? (int) ($_GET['page']) : 0;
  		$startRecord = ($page > 1) ? ($page - 1) * 20 : 0;

  		$query = $dba->select('cms_user')->where('!disabled,((:group_id=-1,group_id=-1)|group_id!=-1),user_id!=?')->limit($startRecord, 20)->query([
  			'user_id'  => $this->userdata['user_id'],
  			'group_id' => $this->userdata['group_id'],
  		]);
  		while ($result = $query->fetch()) {
  			$root->newBlock('record')->assign([
  				'user_id'      => $result['user_id'],
  				'display_name' => $result['display_name'],
  				'login_name'   => $result['login_name'],
  				'last_login'   => $result['last_login'],
  				'email'        => $result['email'],
  			]);
  		}

  		$totalRows = $dba->select('cms_user', 'COUNT(*) as total')->where('!disabled,((:group_id=-1,group_id=-1)|group_id!=-1),user_id!=?')->lazy([
  			'user_id'  => $this->userdata['user_id'],
  			'group_id' => $this->userdata['group_id'],
  			'keyword'  => '',
  		]);

  		$pagination = new Pagination(['item_per_page' => 20]);
  		$root->assign([
  			'pagination' => $pagination->parse($totalRows['total']),
  		]);

  		$this->manager->execute('core.loadFooter');
  	}
  }
}
