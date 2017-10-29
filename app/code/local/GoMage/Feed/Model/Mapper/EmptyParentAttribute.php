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
 * @version      Release: 4.0.0
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Mapper_EmptyParentAttribute extends GoMage_Feed_Model_Mapper_ParentAttribute
{

    /**
     * @param  Varien_Object $object
     * @return mixed
     */
    public function map(Varien_Object $object)
    {
        $result = $this->_mapper->map($object);
        if (!empty($result)) {
            return $result;
        }
        $childProduct = $this->_getChildProduct($object);
        if ($childProduct) {
            return $this->_mapper->map($childProduct);
        }
        return '';
    }

    /**
     * @param Varien_Object $object
     * @return bool|Varien_Object
     */
    protected function _getChildProduct(Varien_Object $object)
    {
        $childId = $this->_connection
            ->select()
            ->from($this->_resource->getTableName('catalog_product_relation'), 'parent_id')
            ->where('parent_id = ?', $object->getId())
            ->where('child_id != ?', $object->getId())
            ->query()
            ->fetchColumn();

        if ($childId) {
            /** @var Mage_Catalog_Model_Resource_Product_Collection $collection */
            $collection = Mage::getModel('gomage_feed/product_collection');
            if ($object->getStoreId()) {
                $collection->setStoreId($object->getStoreId());
                $collection->addStoreFilter($object->getStoreId());
            }
            $collection->addAttributeToSelect($this->getUsedAttributes())
                ->addIdFilter($childId)
                ->setPageSize(1);
            return $collection->getFirstItem();
        }
        return false;
    }

}