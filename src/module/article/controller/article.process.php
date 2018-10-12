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
  return function ($article_id = 0) {
  	$route = $this->module->getRouteName();

  	$this->manager->execute('user.checkArticleLogin');

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
  	$userdata   = $this->manager->execute('user.getArticle');

  	// Prevented Default Value
  	$prevented     = [];
  	$articleResult = new DataFactory();

  	if ('edit' === $route) {
  		// Obtain user information
  		$articleResult = $dba->select('cms_article')->where('article_id=?,!disabled')->lazy([
  			'article_id'      => $article_id,
  		]);
  		if (!$articleResult) {
  			$this->manager->execute('core.halt', 'Article Not Found', 'Article was not found in database or it has been removed.', [
  				CORE_BASE_URL                                 => 'Back to Dashboard',
  				$this->module->getModuleRootURL() => 'Back to Article Management',
  			]);

  			return true;
  		}
  		$articleResult = new DataFactory($articleResult);
  		$prevented     = $articleResult->getArrayCopy();
  	}

  	$formTarget = $this->module->getModuleRootURL() . $route . (('edit' === $route) ? '/' . $article_id : '');

  	if (count($_POST)) {
  		$errors   = [];
  		$postData = new DataFactory($_POST);

  		$postData('subject')->trim();
  		if (!$postData['subject']) {
  			$errors['subject'] = 'The article subject cannot be empty.';
  		}

  		$postData('content')->trim();
  		if (!$postData['content']) {
  			$errors['content'] = 'The article content cannot be empty.';
  		}

  		if ('create' === $route) {
  			$datetime              = new \DateTime('now');
  			$postData['post_date'] = $datetime->format('Y-m-d H:i:s');
  		}

  		if ($errors) {
  			$articleResult->setkeys($postData);
  			foreach ($errors as $field => $errmsg) {
  				$root->assign('err_' . $field, $errmsg);
  			}
  		} else {
  			$parameters = [
  				'subject'   => $postData['subject'],
  				'content'   => $postData['content'],
  				'post_date' => $postData['post_date'],
  			];

  			if ('create' === $route) {
  				$dba->createInsertSQL('cms_article', ['subject', 'content', 'post_date'])->query($parameters);
  			} else {
  				$parameters['article_id'] = $article_id;
  				$dba->createUpdateSQL('cms_article', ['subject', 'content'])->where('article_id=?')->query($parameters);
  			}

  			$this->manager->execute('core.halt', 'System Message', ('edit' === $route) ? 'Article update successfully' : 'Article create successfully', [
  				CORE_BASE_URL                                 => 'Back to Dashboard',
  				$this->module->getModuleRootURL() => 'Back to Article Management',
  			]);

  			return true;
  		}
  	}

  	$root->assign([
  		'form_target'   => $formTarget,
  		'val_subject'   => $articleResult['subject'],
  		'val_content'   => $articleResult['content'],
  		'val_post_date' => $articleResult['post_date'],
  	]);

  	$this->manager->execute('core.loadFooter');

  	return true;
  };
}
