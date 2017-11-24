<?php
/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2017 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.1.0
 * @since        Class available since Release 1.1
 */

$installer = $this;

$installer->startSetup();

$installer->run("
ALTER TABLE `{$this->getTable('gomage_feed_entity')}`
  ADD COLUMN `ftp_active` int(1) DEFAULT 0,
  ADD COLUMN `ftp_host` varchar(128) DEFAULT '',
  ADD COLUMN `ftp_user_name` varchar(256) DEFAULT '',
  ADD COLUMN `ftp_user_pass` varchar(256) DEFAULT '',
  ADD COLUMN `ftp_dir` varchar(256) DEFAULT '/';
"
);

$installer->endSetup();