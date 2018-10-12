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
  class core extends IController
  {
  	protected $menuItem     = [];
  	protected $headerLoaded = false;
  	protected $footerLoaded = false;

  	protected function __onModuleLoaded()
  	{
  		return true;
  	}

  	public function __onReady()
  	{
  		// Razy will not start routing before Ready Stage, so you should use
  		// getRoute() to get the estimated route
  		$route  = $this->module->getRoute();
  		$config = $this->load->config('database');

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
        $dba = $this->load->db('local');
        $dba->connect($config['host'], $config['username'], $config['password'], $config['database']);
      }

  		// Set Pagination Parser
  		Pagination::SetParser(function () {
  			if ($this->max <= 1) {
  				return '';
  			}

  			$tplManager = new TemplateManager(SHARED_VIEW_PATH . '/pagination.tpl');
  			$root = $tplManager->getRootBlock();

  			$prev_url  = '';
  			$next_url  = '';
  			$last_url  = '';
  			$first_url = '';

  			if ($this->start > 1) {
  				$first_url = $this->base_url . (($this->query_string) ? '?' . http_build_query($this->query_string) : '');

  				$this->query_string['page'] = $this->start - 1;
  				$prev_url = $this->base_url . (($this->query_string) ? '?' . http_build_query($this->query_string) : '');
  			}

  			if ($this->end - $this->current > 1) {
  				$this->query_string['page'] = $this->current + 1;
  				$next_url = $this->base_url . (($this->query_string) ? '?' . http_build_query($this->query_string) : '');

  				$this->query_string['page'] = $this->max;
  				$last_url = $this->base_url . (($this->query_string) ? '?' . http_build_query($this->query_string) : '');
  			}

  			$root->assign([
  				'prev_url'  => $prev_url,
  				'next_url'  => $next_url,
  				'first_url' => $first_url,
  				'last_url'  => $last_url,
  			]);

  			foreach ($this->tags as $tag) {
  				$root->newBlock('page')->assign([
  					'url'     => $tag['url'],
  					'page'    => $tag['page'],
  					'current' => $tag['page'] == $this->current,
  				]);
  			}

  			return $tplManager->output(true);
  		});

  		return true;
  	}

  	public function setMenu(string $category, string $name, ModulePackage $module)
  	{
  		$category = trim($category);
  		$name     = trim($name);
  		if (!$name || !$category) {
  			return false;
  		}
  		if (!isset($this->menuItem[$category])) {
  			$this->menuItem[$category] = [];
  		}
  		$this->menuItem[$category][$name] = $module;

  		return true;
  	}

  	public function loadHeader()
  	{
  		if ($this->headerLoaded) {
  			return true;
  		}

  		$this->headerLoaded = true;
  		$tplManager         = $this->load->view('header')->addToQueue('header');
  		$root               = $tplManager->getRootBlock();
  		$userdata           = $this->manager->execute('user.getUser');

  		foreach ($this->menuItem as $categoryName => $menus) {
  			foreach ($menus as $menuName => $module) {
      		if ($this->manager->execute('group.auth', $module, 'view', false)) {
      			$category = $root->newBlock('category', $categoryName)->assign([
      				'name' => $categoryName,
      			]);
    				$category->newBlock('menu', $module->getCode())->assign([
    					'name' => $menuName,
    					'path' => $module->getModuleRootURL(),
    				]);
          }
  			}
  		}

  		$root->assign([
  			'display_name' => $userdata['display_name'],
  			'group_name'   => $userdata['group_name'],
  		]);
  	}

  	public function loadFooter()
  	{
  		if ($this->footerLoaded) {
  			return true;
  		}

  		$this->footerLoaded = true;
  		$this->load->view('footer')->addToQueue('footer');

  		// Output all queued template
  		TemplateManager::OutputQueued(['header', 'body', 'footer']);
  	}
  }
}
