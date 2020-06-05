<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2020 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/license-agreement/  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.3.2
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Content_Csv extends GoMage_Feed_Model_Content_AbstractContent
{

    /**
     * @return GoMage_Feed_Model_Feed_Row_Collection
     */
    public function getRows()
    {
        if (is_null($this->_rows)) {

            $this->_rows = Mage::getModel('gomage_feed/feed_row_collection');

            $content = Zend_Json::decode($this->_content);

            foreach ($content as $data) {

                /** @var GoMage_Feed_Model_Feed_Row_Data $rowData */
                $rowData = Mage::getModel('gomage_feed/feed_row_data', $data);

                /** @var GoMage_Feed_Model_Feed_Row $row */
                $row = Mage::getModel('gomage_feed/feed_row', $rowData);

                $this->_rows->add($row);
            }
        }

        return $this->_rows;
    }

    /**
     * @return string
     */
    public function getEntityType()
    {
        return 'product';
    }
}
