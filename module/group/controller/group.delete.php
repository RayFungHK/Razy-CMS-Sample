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
  return function ($group_id = 0, $confirm = null) {
  	$route = $this->module->getRouteName();

  	$this->manager->execute('user.checkGroupLogin');

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
  	$groupResult = $dba->select('cms_group')->where('group_id=?')->lazy([
  		'group_id' => $group_id,
  	]);
  	if (!$groupResult) {
  		$this->manager->execute('core.halt', 'Group Not Found', 'Group was not found in database or it has been removed.', [
  			URL_BASE                                 => 'Back to Dashboard',
  			URL_BASE . $this->module->getRemapPath() => 'Back to Group Management',
  		]);

  		return true;
  	}

  	$groupResult = new DataFactory($groupResult);

  	if ('confirm' === $confirm) {
  		$dba->createUpdateSQL('cms_group', ['disabled'])->where('group_id=?')->query([
  			'disabled' => 1,
  			'group_id'  => $group_id,
  		]);

      $dba->createUpdateSQL('cms_user', ['group_id'])->where('group_id=:deleted_group')->query([
        'group_id' => 0,
        'deleted_group'  => $group_id,
      ]);

  		$this->manager->execute('core.halt', 'System Message', 'Group remove successfully', [
  			URL_BASE                                 => 'Back to Dashboard',
  			URL_BASE . $this->module->getRemapPath() => 'Back to Group Management',
  		]);

  		return true;
  	}

  	$root->assign([
  		'title'        => 'Confirm delete this group',
  		'message'      => 'Are you sure to delete this group?',
  		'confirm_text' => 'Sure, Delete it.',
  		'confirm_url'  => URL_BASE . $this->module->getRemapPath() . 'delete/' . $group_id . '/confirm',
  		'cancel_url'   => URL_BASE . $this->module->getRemapPath(),
  	]);

  	$this->manager->execute('core.loadFooter');
  };
}
