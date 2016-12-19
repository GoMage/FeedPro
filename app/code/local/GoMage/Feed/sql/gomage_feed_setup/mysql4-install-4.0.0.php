<?php
/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2016 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 3.7.0
 * @since        Class available since Release 4.0.0
 */

$installer = $this;

$installer->startSetup();

$installer->run("
CREATE TABLE `{$this->getTable('gomage_feed_entity')}` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `store_id` smallint(6) NOT NULL,
  `type` varchar(32) NOT NULL,
  `status` smallint(1) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `generated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `cron_started_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `uploaded_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `show_headers` tinyint(1) DEFAULT NULL,
  `enclosure` varchar(32) DEFAULT NULL,
  `delimiter` varchar(32) DEFAULT NULL,
  `remove_lb` tinyint(1) DEFAULT '0',
  `ftp_active` int(1) DEFAULT '0',
  `ftp_host` varchar(128) DEFAULT '',
  `ftp_user_name` varchar(255) DEFAULT '',
  `ftp_user_pass` varchar(255) DEFAULT '',
  `ftp_dir` varchar(255) DEFAULT '/',
  `ftp_passive_mode` tinyint(1) DEFAULT '0',
  `iteration_limit` int(32) DEFAULT '0',
  `upload_day` varchar(32) DEFAULT NULL,
  `upload_hour` smallint(6) DEFAULT NULL,
  `upload_hour_to` smallint(6) DEFAULT NULL,
  `upload_interval` smallint(6) DEFAULT NULL,
  `use_layer` tinyint(1) NOT NULL DEFAULT '1',
  `use_disabled` tinyint(1) NOT NULL DEFAULT '1',
  `restart_cron` smallint(6) DEFAULT NULL,
  `visibility` tinyint(1) NOT NULL DEFAULT '4',
  `use_addition_header` tinyint(1) DEFAULT '0',
  `addition_header` text,
  `upload_status` tinyint(1) NOT NULL DEFAULT '0',
  `cron_uploaded_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `generate_status` tinyint(1) NOT NULL DEFAULT '0',
  `generate_day` varchar(32) DEFAULT NULL,
  `generate_hour` smallint(6) DEFAULT NULL,
  `generate_hour_to` smallint(6) DEFAULT NULL,
  `generate_interval` smallint(6) DEFAULT NULL,
  `generation_time` varchar(50) DEFAULT NULL,
  `filename_ext` varchar(10) DEFAULT NULL,
  `filter_type` tinyint(1) NOT NULL DEFAULT '0',
  `delimiter_prefix` varchar(32) DEFAULT NULL,
  `delimiter_sufix` varchar(32) DEFAULT NULL,
  `ftp_port` varchar(128) DEFAULT '',
  `ftp_protocol` varchar(128) DEFAULT '',
  `conditions_serialized` text,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='GoMage Catalog Feed' AUTO_INCREMENT=1;
"
);
$installer->run("
CREATE TABLE `{$this->getTable('gomage_feed_custom_attribute')}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(128) NOT NULL,
  `name` varchar(128) NOT NULL,
  `default_type` varchar(32) DEFAULT NULL,
  `default_value` varchar(128) NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='GoMage Catalog Feed' AUTO_INCREMENT=1;
"
);
$installer->endSetup(); 