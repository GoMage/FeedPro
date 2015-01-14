<?php
 /**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2015 GoMage.com (http://www.gomage.com)
 * @author       GoMage.com
 * @license      http://www.gomage.com/licensing  Single domain license
 * @terms of use http://www.gomage.com/terms-of-use
 * @version      Release: 3.4
 * @since        Class available since Release 1.1
 */

$installer = $this;

$installer->startSetup();

$installer->run("
CREATE TABLE IF NOT EXISTS `{$this->getTable('gomage_feed_entity')}` (
  `id` smallint(6) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `store_id` smallint(6) NOT NULL,
  `type` smallint(1) NOT NULL,
  `status` smallint(1) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `filter` text,
  `generated_at` datetime NOT NULL default '0000-00-00 00:00:00',
  `ftp_active` int(1) DEFAULT 0,
  `ftp_host` varchar(128) DEFAULT '',
  `ftp_user_name` varchar(256) DEFAULT '',
  `ftp_user_pass` varchar(256) DEFAULT '',
  `ftp_dir` varchar(256) DEFAULT '/',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='GoMage Catalog Feed' AUTO_INCREMENT=1;
");

$installer->endSetup(); 