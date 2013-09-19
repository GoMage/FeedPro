<?php
 /**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2013 GoMage.com (http://www.gomage.com)
 * @author       GoMage.com
 * @license      http://www.gomage.com/licensing  Single domain license
 * @terms of use http://www.gomage.com/terms-of-use
 * @version      Release: 3.2
 * @since        Class available since Release 3.0
 */

$installer = $this;
$installer->startSetup();
$installer->run("
ALTER TABLE `{$this->getTable('gomage_feed_entity')}` ADD COLUMN `delimiter_prefix` varchar(32) default NULL;
ALTER TABLE `{$this->getTable('gomage_feed_entity')}` ADD COLUMN `delimiter_sufix` varchar(32) default NULL;
");

$installer->endSetup();