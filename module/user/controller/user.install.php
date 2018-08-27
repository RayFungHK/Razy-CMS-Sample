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
  return function () {
  	$config     = $this->load->config('database');
  	if ($config->isLoaded()) {
  		// If the config file has loaded successfully, end user suppose cannot visit
  		// installation page. Return false to send 404 header
  		return false;
  	}
  	$config = $this->load->config('install');

  	if ($config->isLoaded()) {
  		// If the config file has loaded successfully, end user suppose cannot visit
  		// installation page. Return false to send 404 header
  		return false;
  	}

  	$tplManager = $this->load->view('install');

  	$root = $tplManager->getRootBlock();
  	$root->assign([
  		'module_code' => $this->module->getCode(),
  		'version'     => $this->module->getVersion(),
  	]);

  	$parameters = new DataFactory();
  	$success    = false;
  	$errors     = [];
  	if ($_POST) {
  		$parameters = new DataFactory($_POST);

  		$parameters('display_name')->trim();
  		if (!$parameters['display_name']) {
  			$errors[] = 'Display name cannot be empty.';
  		}

  		$parameters('username')->trim();
  		if (!$parameters['username']) {
  			$errors[] = 'Username cannot be empty.';
  		}

  		$parameters('password')->trim();
  		if (!$parameters['password']) {
  			$errors[] = 'Password cannot be empty.';
  		}

  		$parameters('email')->trim();

  		if (!count($errors)) {
  			$dba = $this->load->db('local');

  			$config['installed']    = true;
  			$config['install_time'] = time();
  			$config['version']      = $this->module->getVersion();

  			$userTable = new DatabaseTable('cms_user');
  			$userTable->createColumn('user_id', Database::COLUMN_AUTO_ID)
				->createColumn('login_name', Database::COLUMN_TEXT)
				->createColumn('password', Database::COLUMN_TEXT)
				->createColumn('group_id', Database::COLUMN_INT)
				->createColumn('display_name', Database::COLUMN_TEXT)
				->createColumn('profile_image', Database::COLUMN_TEXT)
				->createColumn('email', Database::COLUMN_TEXT)
				->createColumn('session_key', Database::COLUMN_TEXT, ['length' => 32])
				->createColumn('last_login', Database::COLUMN_DATETIME)
				->createColumn('disabled', Database::COLUMN_BOOLEAN);

  			$dba->query($userTable);

  			$dba->createInsertSQL('cms_user', ['login_name', 'password', 'group_id', 'display_name', 'profile_image', 'session_key', 'disabled'])->query([
  				'login_name'    => $parameters['username'],
  				'password'      => md5($parameters['password']),
  				'group_id'      => -1,
  				'email'         => '',
  				'last_login'    => null,
  				'display_name'  => $parameters['display_name'],
  				'profile_image' => '',
  				'session_key'   => '',
  				'disabled'      => 0,
  			]);

  			$config->commit();
  			$root->newBlock('success');
  			$success = true;
  		}
  	}

  	if (!$success) {
  		$setting = $root->newBlock('setting');
  		if ($errors) {
  			$failed = $setting->newBlock('failed');
  			foreach ($errors as $field => $text) {
  				$failed->assign('error', implode('<br />', $errors));
  			}
  		}

  		$setting->assign([
  			'module_url'       => $this->module->getModuleRootURL() . 'install',
  			'val_username'     => ($parameters['display_name']) ? $parameters['username'] : 'superuser',
  			'val_display_name' => ($parameters['display_name']) ? $parameters['display_name'] : 'Super User',
  			'val_email'        => $parameters['email'],
  		]);
  	}
  	$tplManager->output();

  	return true;
  };
}
