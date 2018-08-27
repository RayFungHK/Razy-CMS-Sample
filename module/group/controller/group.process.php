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
  return function ($group_id = 0) {
  	$route = $this->module->getRouteName();

  	$this->manager->execute('user.checkGroupLogin');

    // Check permission
    if (!$this->manager->execute('group.auth', $this->module, $route)) {
      return true;
    }

  	// Load header
  	$this->manager->execute('core.loadHeader');

  	// Load `form.tpl` template file
  	$tplManager = $this->load->view('form')->addToQueue('body');
  	$root       = $tplManager->getRootBlock();
  	$dba        = $this->load->db('local');
  	$userdata   = $this->manager->execute('user.getGroup');

  	// Prevented Default Value
  	$prevented   = [];
  	$groupResult = new DataFactory();

  	if ('edit' === $route) {
  		// Obtain user information
  		$groupResult = $dba->select('cms_group')->where('group_id=?,!disabled')->lazy([
  			'group_id'      => $group_id,
  		]);
  		if (!$groupResult) {
  			$this->manager->execute('core.halt', 'Group Not Found', 'Group was not found in database or it has been removed.', [
  				URL_BASE                                 => 'Back to Dashboard',
  				URL_BASE . $this->module->getRemapPath() => 'Back to Group Management',
  			]);

  			return true;
  		}
  		$groupResult = new DataFactory($groupResult);
  		$prevented   = $groupResult->getArrayCopy();
  	}

  	$formTarget = URL_BASE . $this->module->getRemapPath() . $route . (('edit' === $route) ? '/' . $group_id : '');

  	if (count($_POST)) {
  		$errors   = [];
  		$postData = new DataFactory($_POST);

  		$postData('name')->trim();
  		if (!$postData['name']) {
  			$errors['name'] = 'The group name cannot be empty.';
  		}

      $postData('permission')->json_encode();

  		if ($errors) {
  			$groupResult->setkeys($postData);
  			foreach ($errors as $field => $errmsg) {
  				$root->assign('err_' . $field, $errmsg);
  			}
  		} else {
  			$parameters = [
  				'name'       => $postData['name'],
  				'permission' => $postData['permission'],
  			];

  			if ('create' === $route) {
  				$dba->createInsertSQL('cms_group', ['name', 'permission'])->query($parameters);
  			} else {
          $parameters['group_id'] = $group_id;
  				$dba->createUpdateSQL('cms_group', ['name', 'permission'])->where('group_id=?')->query($parameters);
  			}

  			$this->manager->execute('core.halt', 'System Message', ('edit' === $route) ? 'Group update successfully' : 'Group create successfully', [
  				URL_BASE                                 => 'Back to Dashboard',
  				URL_BASE . $this->module->getRemapPath() => 'Back to Group Management',
  			]);

  			return true;
  		}
  	}

    $groupResult('permission')->json_decode(true);
  	if (count($this->permissions)) {
  		foreach ($this->permissions as $moduleCode => $permissionData) {
  			if (count($permissionData['permission'])) {
    			$category_block = $root->newBlock('category', $permissionData['category_name']);
          $category_block->assign('category_name', $permissionData['category_name']);

          $module_block = $category_block->newBlock('module');
          $module_block->assign('module_name', $permissionData['module_name']);

  				foreach ($permissionData['permission'] as $action => $optionName) {
  					$module_block->newBlock('option')->assign(array(
  						'name' => $optionName,
  						'action' => $action,
  						'module_name' => $moduleCode,
  						'checked' => (isset($groupResult['permission'][$moduleCode][$action]))
  					));
  				}
  			}
  		}
  	}

  	$root->assign([
  		'form_target' => $formTarget,
  		'val_name'    => $groupResult['name'],
  	]);

  	$this->manager->execute('core.loadFooter');

  	return true;
  };
}
