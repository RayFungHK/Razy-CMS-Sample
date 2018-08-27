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
  	$tplManager = $this->load->view('install');

  	$root       = $tplManager->getRootBlock();
  	$root->assign([
  		'module_code' => $this->module->getCode(),
  		'version'     => $this->module->getVersion(),
  	]);

  	$parameters = new DataFactory();
  	$success    = false;
  	$errors     = [];
  	if ($_POST) {
  		$parameters = new DataFactory($_POST);

  		$parameters('host')->trim();
  		if (!$parameters['host']) {
  			$errors[] = 'Host cannot be empty.';
  		}

  		$parameters('username')->trim();
  		if (!$parameters['username']) {
  			$errors[] = 'Username cannot be empty.';
  		}

  		$parameters('password')->trim();
  		if (!$parameters['password']) {
  			$errors[] = 'Password cannot be empty.';
  		}

  		$parameters('database')->trim();
  		if (!$parameters['database']) {
  			$errors[] = 'Database cannot be empty.';
  		}

  		if (!count($errors)) {
  			$dba = $this->load->db('local');

  			try {
  				if (!$dba->connect($parameters['host'], $parameters['username'], $parameters['password'], $parameters['database'])) {
  					$errors[] = 'Cannot connect to database';
  				}
  			} catch (Exception $e) {
  				$errors[] = $e;
  			}

  			if (!count($errors)) {
  				$config['host']     = $parameters['host'];
  				$config['username'] = $parameters['username'];
  				$config['password'] = $parameters['password'];
  				$config['database'] = $parameters['database'];
  				$config->commit();
  				$root->newBlock('success');
  				$success = true;
  			}
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
  			'module_url'   => $this->module->getModuleRootURL() . 'install',
  			'val_username' => $parameters['username'],
  			'val_host'     => ($parameters['host']) ? $parameters['host'] : 'localhost',
  			'val_database' => $parameters['database'],
  		]);
  	}

  	$tplManager->output();

  	return true;
  };
}
