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
class GoMage_Feed_Model_Reader_Collection
{

    const SORT_ATTRIBUTE = 'entity_id';

    /**
     * @var GoMage_Feed_Model_Product_Collection
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
     * @return GoMage_Feed_Model_Product_Collection
     */
    public function read($page, $limit)
    {
        $collection = $this->_getCollection();

        $collection->setPage($page, $limit);
        if (!$collection->getSize()) {
            return false;
        }
        if ($page > $collection->getLastPageNumber()) {
            return false;
        }
        return $collection;
    }

    /**
     * @return GoMage_Feed_Model_Product_Collection
     */
    protected function _getCollection()
    {
        if (is_null($this->_collection)) {
            $this->_collection = Mage::getModel('gomage_feed/product_collection');

            if ($this->_params->getStoreId()) {
                $this->_collection->setStoreId($this->_params->getStoreId());
            }

            $visibility = Mage::getModel('gomage_feed/adminhtml_system_config_source_visibility')
                ->getProductVisibility($this->_params->getVisibility());
            if (is_array($visibility) && !empty($visibility)) {
                $this->_collection->addAttributeToFilter('visibility', ['in' => $visibility]);
            }

            if ($this->_params->getIsDisabled()) {
                $this->_collection->addAttributeToFilter('status', ['eq' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED]);
            }

            if (in_array($this->_params->getUseLayer(), array(GoMage_Feed_Model_Adminhtml_System_Config_Source_Uselayer::NO,
                    GoMage_Feed_Model_Adminhtml_System_Config_Source_Uselayer::NO_WITH_CHILD)
            )) {
                $this->_collection->joinField('is_in_stock', 'cataloginventory/stock_item', 'is_in_stock', 'product_id=entity_id');
                $this->_collection->addAttributeToFilter('is_in_stock', array('eq' => Mage_CatalogInventory_Model_Stock::STOCK_IN_STOCK));
            }

            $this->_params->getConditions()->collectValidatedAttributes($this->_collection);
            $where = $this->_params->getConditions()->prepareConditionSql();
            if (!empty($where)) {
                $this->_collection->getSelect()->where($where);
            }

            $this->_collection->addAttributeToSelect($this->_params->getAttributes())
                ->addAttributeToSort(self::SORT_ATTRIBUTE);



        }
        return $this->_collection->clear();
    }

}