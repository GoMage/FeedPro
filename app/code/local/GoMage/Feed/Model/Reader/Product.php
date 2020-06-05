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
class GoMage_Feed_Model_Reader_Product implements GoMage_Feed_Model_Reader_ReaderInterface
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
    public function read($page = 1, $limit = 0)
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
                $this->_collection->addStoreFilter($this->_params->getStoreId());
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

            $this->joinImageTableForFlat();
            $where = $this->_params->getConditions()->prepareConditionSql();
            if (!empty($where)) {
                $this->_collection->getSelect()->where($where);
            }

            if (($this->_params->getGenerateType() == GoMage_Feed_Model_Adminhtml_System_Config_Source_Generate::CHANGED) &&
                $this->_params->getGeneratedAt()) {
                $this->_collection->addFieldToFilter('updated_at', array('gt' => $this->_params->getGeneratedAt()));
            }

            $this->_collection->addAttributeToSelect($this->_params->getAttributes())
                ->addAttributeToSort(self::SORT_ATTRIBUTE);
        }
        return $this->_collection->clear();
    }

    /**
     * @param  Varien_Object $item
     * @return bool
     */
    public function isValidItem(Varien_Object $item)
    {
        if ($this->_params->getUseLayer() == GoMage_Feed_Model_Adminhtml_System_Config_Source_Uselayer::NO_WITH_CHILD) {
            if ($item->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) {

                /** @var Varien_Db_Adapter_Interface $connection */
                $connection = $this->_collection->getConnection();
                $result     = $connection
                    ->select()
                    ->from($connection->getTableName('catalog_product_relation'), 'child_id')
                    ->join($connection->getTableName('cataloginventory_stock_item'), 'child_id=product_id')
                    ->where('parent_id = ?', $item->getId())
                    ->where('child_id != ?', $item->getId())
                    ->where('is_in_stock = 1')
                    ->query()
                    ->fetchColumn();
                return boolval($result);
            }
        }
        return true;
    }

    /**
     * As in flat there isn't such attribute as image need to join tables with image data.
     *
     * @return void
     */
    private function joinImageTableForFlat()
    {
        if (Mage::getStoreConfigFlag('catalog/frontend/flat_catalog_product')) {
            $attribute = 'image';
            $issetImageCondition = false;

            $conditions = $this->_params->getConditions()->getConditions();
            foreach ($conditions as $condition) {
                if ($condition->getAttribute() === $attribute) {
                    $issetImageCondition = true;
                    break;
                }
            }

            if ($issetImageCondition === true) {
                $storeId = $this->_params->getStoreId();
                $defaultStoreId = Mage_Catalog_Model_Abstract::DEFAULT_STORE_ID;

                /** @var Mage_Catalog_Model_Resource_Product_Collection $productCollection */
                $productCollection = Mage::getResourceModel('catalog/product_collection');
                $attributeId = $productCollection->getEntity()->getAttribute($attribute)->getId();
                $tableName = Mage::getSingleton('core/resource')->getTableName('catalog_product_entity_varchar');

                $this->_collection->getSelect()->joinLeft(
                    ['at_image' => $tableName],
                    '(at_image.entity_id = e.entity_id) AND (at_image.attribute_id = ' . $attributeId
                    . ') AND (at_image.store_id = ' . $storeId . ')',
                    ['IF(at_image.value_id > 0, at_image.value, at_image_default.value) AS image']
                );
                $this->_collection->getSelect()->joinLeft(
                    ['at_image_default' => $tableName],
                    '(at_image_default.entity_id = e.entity_id) AND (at_image_default.attribute_id = ' . $attributeId
                    . ') AND (at_image_default.store_id = ' . $defaultStoreId . ')',
                    []
                );
            }
        }
    }
}
