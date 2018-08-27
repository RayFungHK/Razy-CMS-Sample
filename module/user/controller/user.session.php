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
  	$tplManager = $this->load->view('session')->addToQueue('body');
  	$root       = $tplManager->getRootBlock();
  	$dba        =  $this->load->db('local');
  	$redirect   = '';
  	$login_name = '';

  	if (isset($_GET['signout'])) {
  		unset($_SESSION['login']);
  		setcookie('login', null, time() - 1);
  		$reference = '';
  	} elseif (count($_POST)) {
  		$error_message = '';
  		if (isset($_GET['redirect'])) {
  			$redirect = path(SYSTEM_ROOT, urldecode($_GET['redirect']));
  		}

  		$login_name = (isset($_POST['username'])) ? $_POST['username'] : '';
  		$password   = (isset($_POST['password'])) ? $_POST['password'] : '';

  		if (!$password || !$login_name) {
  			$error_message = 'Please input your login name and password';
  		} else {
  			$password = md5($password);
  			if (!$error_message) {
  				$result = $dba->select('cms_user')->where('login_name=?,password=?,!disabled')->lazy([
  					'login_name' => $login_name,
  					'password'   => $password,
  				]);
  				if ($result) {
  					$session_key = uniqid();
  					$dba->prepare('UPDATE cms_user SET last_login = NOW(), session_key = :session_key')->where('user_id=?')->query([
  						'session_key' => $session_key,
  						'user_id'     => $result['user_id'],
  					]);

  					$login_data = ['login_name' => $login_name, 'password' => $password, 'session_key' => $session_key];
  					if (isset($_GET['remember_me'])) {
  						setcookie('login', json_encode($login_data), time() + 86400);
  					} else {
  						$_SESSION['login'] = $login_data;
  					}

  					header('location: ' . (($redirect) ? $redirect : URL_BASE));
  				} else {
  					$error_message = 'Invalid Username or Password';
  				}
  			}
  		}

  		if ($error_message) {
  			$root->newBlock('error')->assign([
  				'error_message' => $error_message,
  			]);
  		}
  	}

  	$root->assign([
  		'form_target'  => URL_BASE . $this->module->getRemapPath() . 'session',
  		'val_username' => $login_name,
  	]);

  	echo $tplManager->output();
  };
}
