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
  return function ($article_id = 0, $confirm = null) {
  	$route = $this->module->getRouteName();

  	$this->manager->execute('user.checkArticleLogin');

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
  	$articleResult = $dba->select('cms_article')->where('article_id=?')->lazy([
  		'article_id' => $article_id,
  	]);
  	if (!$articleResult) {
  		$this->manager->execute('core.halt', 'Article Not Found', 'Article was not found in database or it has been removed.', [
  			CORE_BASE_URL                                 => 'Back to Dashboard',
  			$this->module->getModuleRootURL() => 'Back to Article Management',
  		]);

  		return true;
  	}

  	$articleResult = new DataFactory($articleResult);

  	if ('confirm' === $confirm) {
  		$dba->createUpdateSQL('cms_article', ['disabled'])->where('article_id=?')->query([
  			'disabled' => 1,
  			'article_id'  => $article_id,
  		]);

  		$this->manager->execute('core.halt', 'System Message', 'Article remove successfully', [
  			CORE_BASE_URL                                 => 'Back to Dashboard',
  			$this->module->getModuleRootURL() => 'Back to Article Management',
  		]);

  		return true;
  	}

  	$root->assign([
  		'title'        => 'Confirm delete this article',
  		'message'      => 'Are you sure to delete this article?',
  		'confirm_text' => 'Sure, Delete it.',
  		'confirm_url'  => $this->module->getModuleRootURL() . 'delete/' . $article_id . '/confirm',
  		'cancel_url'   => $this->module->getModuleRootURL(),
  	]);

  	$this->manager->execute('core.loadFooter');
  };
}
