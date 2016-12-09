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

//TODO: add other versions

$installer = $this;
$installer->startSetup();

$installer->run("ALTER TABLE `{$this->getTable('gomage_feed_entity')}` ADD COLUMN `conditions_serialized` text default NULL;");

$installer->endSetup();