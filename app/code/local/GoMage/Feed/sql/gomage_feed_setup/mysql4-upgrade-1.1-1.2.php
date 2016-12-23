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
 * @version      Release: 4.0.0
 * @since        Class available since Release 1.2
 */

$installer = $this;

$installer->startSetup();

$installer->run("
ALTER TABLE `{$this->getTable('gomage_feed_entity')}`
  ADD COLUMN `ftp_passive_mode` tinyint(1) DEFAULT 0;
"
);

$installer->endSetup();