<?php

/*
 * This file is part of RazyFramwork.
 *
 * (c) Ray Fung <hello@rayfung.hk>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace RazyFramework
{
  return function () {
    $config = $this->load->config('install');

    if ($config->isLoaded()) {
      // If the config file has loaded successfully, end user suppose cannot visit
      // installation page. Return false to send 404 header
      return false;
    }

    if ($this->manager->moduleIsReady('user')) {
      $dba                    = $this->load->db('local');
      $config['installed']    = true;
      $config['install_time'] = time();
      $config['version']      = $this->module->getVersion();

      $articleTable = new DatabaseTable('cms_article');
      $articleTable->createColumn('article_id', Database::COLUMN_AUTO_ID)
      ->createColumn('subject', Database::COLUMN_TEXT)
      ->createColumn('content', Database::COLUMN_LONG_TEXT)
      ->createColumn('post_date', Database::COLUMN_DATETIME)
      ->createColumn('disabled', Database::COLUMN_BOOLEAN);
      $dba->query($articleTable);

      $config->commit();

      $this->manager->locate('/');
    }

    $tplManager->output();
    return true;
  };
}
