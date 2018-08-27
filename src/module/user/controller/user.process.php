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
  return function ($user_id = 0) {
  	$route = $this->module->getRouteName();

  	$this->manager->execute('user.checkUserLogin');

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
  	$userdata   = $this->manager->execute('user.getUser');

  	// Prevented Default Value
  	$prevented  = [];
  	$userResult = new DataFactory();

  	if ('edit' === $route) {
  		// Obtain user information
  		$userResult = $dba->select('cms_user')->where('user_id=?,!disabled,((:group=-1,group_id=-1)|group_id!=-1),user_id!=:your_user_id')->lazy([
  			'user_id'      => $user_id,
  			'group_id'     => $userdata['group_id'],
  			'your_user_id' => $userdata['user_id'],
  		]);
  		if (!$userResult) {
  			$this->manager->execute('core.halt', 'User Not Found', 'User was not found in database or it has been removed.', [
  				URL_BASE                                 => 'Back to Dashboard',
  				URL_BASE . $this->module->getRemapPath() => 'Back to User Management',
  			]);

  			return true;
  		}
  		$userResult = new DataFactory($userResult);
  		$prevented  = $userResult->getArrayCopy();
  	}

  	$formTarget = URL_BASE . $this->module->getRemapPath() . $route . (('edit' === $route) ? '/' . $user_id : '');

  	if (count($_POST)) {
  		$errors   = [];
  		$postData = new DataFactory($_POST);

  		$postData('group_id')->int();
  		if ($postData['group_id'] !== $userResult['group_id'] && -1 === $userResult['group_id']) {
  			$result = $dba->select('cms_user')->where('group=-1,user!=?,!disabled')->lazy([
  				'user_id' => $user_id,
  			]);
  			if (!$result) {
  				$errors['group_id'] = 'You should setup at least 1 super admin account.';
  			}
  		} else {
  			if (-1 !== $this->userdata['group_id'] && -1 === $postData['group_id']) {
  				$errors['group_id'] = 'You have no right to edit any super admin account.';
  			}
  		}

  		$postData('login_name')->trim();
  		if (!$postData['login_name']) {
  			$errors['login_name'] = 'The login name cannot be empty.';
  		} else {
  			$result = $dba->select('cms_user')->where('login_name=?,user_id!=?,!disabled')->lazy([
  				'login_name' => $postData['login_name'],
  				'user_id'    => $user_id,
  			]);
  			if ($result) {
  				$errors['login_name'] = 'The user login name existed, please input another login name.';
  			}
  		}

  		$postData('display_name')->trim();
  		if (!$postData['display_name']) {
  			$errors['display_name'] = 'The display name cannot be empty.';
  		}

  		$postData('password')->trim();
  		$postData('retype_password')->trim();

  		if ('create' === $route) {
  			if (!$postData['password']) {
  				$errors['password'] ='The password cannot be empty.';
  			} elseif ($postData('password')->length() < 6) {
  				$errors['password'] = 'The password lenght must greater than 6 characters.';
  			} elseif ($postData['password'] !== $postData['retype_password']) {
  				var_dump($postData['password'] === $postData['retype_password']);
  				echo $postData['password'];
  				echo $postData['retype_password'];
  				$errors['password'] = 'The password was not match the retype password.';
  			}
  			$postData['password'] = md5($postData['password']);
  		} elseif ('edit' === $route) {
  			if ($postData['password']) {
  				if ($postData['password'] !== $postData['retype_password']) {
  					$errors['password'] = 'The password was not match the retype password.';
  				} elseif ($postData('password')->length() < 6) {
  					$errors['password'] = 'The password lenght must greater than 6 characters.';
  				} else {
  					$postData['password'] = md5($postData['password']);
  				}
  			} else {
  				$postData['password'] = $userResult['password'];
  			}
  		}

  		unset($postData['retype_password']);
  		if ($errors) {
  			$userResult->setkeys($postData);
  			foreach ($errors as $field => $errmsg) {
  				$root->assign('err_' . $field, $errmsg);
  			}
  		} else {
        $parameters = [
          'login_name'   => $postData['login_name'],
          'password'     => $postData['password'],
          'group_id'     => $postData['group_id'],
          'display_name' => $postData['display_name'],
          'email'        => $postData['email'],
        ];

  			if ('create' === $route) {
  				$dba->createInsertSQL('cms_user', ['login_name', 'password', 'group_id', 'display_name', 'email'])->query($parameters);
  			} else {
          $parameters['user_id'] = $user_id;
  				$dba->createUpdateSQL('cms_user', ['login_name', 'password', 'group_id', 'display_name', 'email'])->where('user_id=?')->query($parameters);
  			}

  			$this->manager->execute('core.halt', 'System Message', ('edit' === $route) ? 'User update successfully' : 'User create successfully', [
					URL_BASE                                 => 'Back to Dashboard',
					URL_BASE . $this->module->getRemapPath() => 'Back to User Management',
				]);

  			return true;
  		}
  	}

  	// Load Permission Group
  	// Super admin has full permission, group_id assigned to -1
  	// Anonymous or ghost group has no permission, group_id assigned to 0
  	$userdata['group_id'] = -1;
  	$groupList            = [];
  	if (-1 === $this->userdata['group_id']) {
  		$groupList['-1'] = 'System Admin';
  	}
  	$groupList['0'] = 'Not Assigned';

  	$query = $dba->select('cms_group')->where('!disabled')->query();
  	while ($result = $query->fetch()) {
  		$groupList[$result['group_id']] = $result['name'];
  	}

  	$groupSelecter = new HTMLControl(HTMLControl::TYPE_SELECT, $groupList);
  	$groupSelecter->attribute('name', 'group_id')->setValue($userResult['group_id']);

  	$root->assign([
  		'form_target'      => $formTarget,
  		'selectbox_group'  => $groupSelecter->saveHTML(),
  		'val_user_id'      => $userResult['user_id'],
  		'val_display_name' => $userResult['display_name'],
  		'val_login_name'   => $userResult['login_name'],
  		'val_email'        => $userResult['email'],
  	]);

  	$this->manager->execute('core.loadFooter');

  	return true;
  };
}
