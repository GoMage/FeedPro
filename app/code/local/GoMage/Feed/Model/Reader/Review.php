<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2016 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/license-agreement/  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 3.7.0
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Reader_Review implements GoMage_Feed_Model_Reader_ReaderInterface
{

    /**
     * @var Mage_Review_Model_Resource_Review_Collection
     */
    protected $_collection;

    /**
     * @var GoMage_Feed_Model_Reader_Params
     */
    protected $_params;

    public function __construct($params)
    {
        $this->_params = $params;
    }

    /**
     * @param  int $page
     * @param  int $limit
     * @return Mage_Review_Model_Resource_Review_Collection
     */
    public function read($page, $limit)
    {
        $collection = $this->_getCollection();

        $collection->setCurPage($page)->setPageSize($limit);
        if (!$collection->getSize()) {
            return false;
        }
        if ($page > $collection->getLastPageNumber()) {
            return false;
        }
        return $collection;
    }

    /**
     * @return Mage_Review_Model_Resource_Review_Collection
     */
    protected function _getCollection()
    {
        if (is_null($this->_collection)) {
            $this->_collection = Mage::getResourceModel('review/review_collection');

            if ($this->_params->getStoreId()) {
                $this->_collection->addStoreFilter($this->_params->getStoreId());
            }
            $this->_collection->addStatusFilter(Mage_Review_Model_Review::STATUS_APPROVED);

            Mage::log($this->_collection->getSelect()->__toString(), null, 'f.log');
        }
        return $this->_collection->clear();
    }

    /**
     * @param Varien_Object $item
     * @return bool
     */
    public function isValidItem(Varien_Object $item)
    {
        return true;
    }
}