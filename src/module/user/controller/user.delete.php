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
  return function ($user_id = 0, $confirm = null) {
  	$route = $this->module->getRouteName();

  	$this->manager->execute('user.checkUserLogin');

    // Check permission
    if (!$this->manager->execute('group.auth', $this->module, $route)) {
      return true;
    }

  	$this->manager->execute('core.loadHeader');

  	// Load `delete.tpl` template file
  	$tplManager = $this->load->view('delete')->addToQueue('body');
  	$root       = $tplManager->getRootBlock();
  	$dba        = $this->load->db('local');
  	$userdata   = $this->manager->execute('user.getUser');

  	// Obtain user information
  	$userResult = $dba->select('cms_user')->where('user_id=?,!disabled,((:group=-1,group_id=-1)|group_id!=-1),user_id!=:your_user_id')->lazy([
  		'user_id'      => $user_id,
  		'group_id'     => $userdata['group_id'],
  		'your_user_id' => $userdata['user_id'],
  	]);
  	if (!$userResult) {
  		$this->manager->execute('core.halt', 'User Not Found', 'User was not found in database or it has been removed.', [
  			CORE_BASE_URL                                 => 'Back to Dashboard',
  			$this->module->getModuleRootURL() => 'Back to User Management',
  		]);

  		return true;
  	}

  	$userResult = new DataFactory($userResult);

    // Check system remaining super user
  	$countResult = $dba->select('cms_user', 'COUNT(*) as total')->where('user_id!=?,group_id=-1,!disabled')->lazy([
  		'user_id' => $user_id,
  	]);
  	if (!$countResult['total']) {
  		$this->manager->execute('core.halt', 'System Message', 'Your system should have at least Super Admin.', [
  			CORE_BASE_URL                                 => 'Back to Dashboard',
  			$this->module->getModuleRootURL() => 'Back to User Management',
  		]);

  		return true;
  	}

  	if ('confirm' === $confirm) {
  		$dba->createUpdateSQL('cms_user', ['disabled'])->where('user_id=?')->query([
  			'disabled' => 1,
  			'user_id'  => $user_id,
  		]);

  		$this->manager->execute('core.halt', 'System Message', 'User remove successfully', [
  			CORE_BASE_URL                                 => 'Back to Dashboard',
  			$this->module->getModuleRootURL() => 'Back to User Management',
  		]);

  		return true;
  	}

  	$root->assign([
  		'title'        => 'Confirm delete this user',
  		'message'      => 'Are you sure to delete this user?',
  		'confirm_text' => 'Sure, Delete it.',
  		'confirm_url'  => $this->module->getModuleRootURL() . 'delete/' . $user_id . '/confirm',
  		'cancel_url'   => $this->module->getModuleRootURL(),
  	]);

  	$this->manager->execute('core.loadFooter');
  };
}
