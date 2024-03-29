<?php
/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2020 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.3.2
 * @since        Class available since Release 3.0
 */

$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE `{$this->getTable('gomage_feed_entity')}` ADD COLUMN `upload_status` tinyint(1) NOT NULL default '0';
ALTER TABLE `{$this->getTable('gomage_feed_entity')}` ADD COLUMN `cron_uploaded_at` datetime NOT NULL default '0000-00-00 00:00:00';
"
);

$installer->run("
ALTER TABLE `{$this->getTable('gomage_feed_entity')}` ADD COLUMN `generate_status` tinyint(1) NOT NULL default '0';
ALTER TABLE `{$this->getTable('gomage_feed_entity')}` ADD COLUMN `generate_day` varchar(32) default NULL;
ALTER TABLE `{$this->getTable('gomage_feed_entity')}` ADD COLUMN `generate_hour` smallint(6) default NULL;
ALTER TABLE `{$this->getTable('gomage_feed_entity')}` ADD COLUMN `generate_hour_to` smallint(6) default NULL;
ALTER TABLE `{$this->getTable('gomage_feed_entity')}` ADD COLUMN `generate_interval` smallint(6) default NULL;
"
);

$installer->run("
ALTER TABLE `{$this->getTable('gomage_feed_entity')}` ADD COLUMN `generation_time` varchar(50) default NULL;
"
);

$installer->run("
ALTER TABLE `{$this->getTable('gomage_feed_entity')}` ADD COLUMN `filename_ext` varchar(10) default NULL;
"
);

$sql = $installer->getConnection()->quoteInto("SELECT * FROM `{$installer->getTable('gomage_feed_custom_attribute')}`", array());

$data = $installer->getConnection()->fetchAll($sql);

foreach ($data as $row) {
    if ($row['data']) {
        $row_data = Zend_Json::decode($row['data'], true);
        if ($row_data) {
            foreach ($row_data as $i => $_data) {
                $row_data[$i]['value_type_attribute']   = array();
                $row_data[$i]['value_type_attribute'][] = array('attribute' => $_data['value_type_attribute']);
            }
            $row_data = Zend_Json::encode($row_data);

            $installer->run("UPDATE {$this->getTable('gomage_feed_custom_attribute')}
                 SET `data` = '{$row_data}'
                WHERE id = {$row['id']}"
            );
        }
    }
}

$installer->run("
ALTER TABLE `{$this->getTable('gomage_feed_entity')}` ADD COLUMN `filter_type` tinyint(1) NOT NULL default '0';
"
);

$installer->endSetup();