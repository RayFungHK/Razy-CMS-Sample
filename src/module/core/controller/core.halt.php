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
  return function ($title, $message, $returnUrl = [], $extraContent = '') {
  	$this->loadHeader();

  	$message    = (!is_array($message)) ? [$message] : $message;
  	$returnUrl  = (!is_array($returnUrl)) ? [$returnUrl] : $returnUrl;
  	$tplManager = $this->load->view('halt')->addToQueue('body');
  	$root       = $tplManager->getRootBlock();

  	$root->assign([
  		'title'         => $title,
  		'extra_content' => $extraContent,
  	]);

  	foreach ($message as $msg) {
  		$root->newBlock('message')->assign('message', $msg);
  	}

  	if (count($returnUrl)) {
  		foreach ($returnUrl as $url => $text) {
  			$root->newBlock('return')->assign([
  				'url'  => $url,
  				'text' => $text,
  			]);
  		}
  	}

  	$this->loadFooter();
  };
}
