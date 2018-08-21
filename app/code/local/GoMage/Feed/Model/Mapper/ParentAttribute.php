<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2018 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/license-agreement/  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.3.0
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Mapper_ParentAttribute implements GoMage_Feed_Model_Mapper_MapperInterface
{

    /**
     * @var GoMage_Feed_Model_Mapper_MapperInterface
     */
    protected $_mapper;

    /**
     * @var Mage_Core_Model_Resource
     */
    protected $_resource;

    /**
     * @var Varien_Db_Adapter_Interface
     */
    protected $_connection;


    public function __construct($value)
    {
        $this->_resource   = Mage::getSingleton('core/resource');
        $this->_connection = $this->_resource->getConnection('core_read');

        /** @var GoMage_Feed_Model_Mapper_Factory $mapperFactory */
        $mapperFactory = Mage::getSingleton('gomage_feed/mapper_factory');
        $this->_mapper = $mapperFactory->create(GoMage_Feed_Model_Adminhtml_System_Config_Source_Field_TypeInterface::ATTRIBUTE, $value);
    }

    /**
     * @param  Varien_Object $object
     * @return mixed
     */
    public function map(Varien_Object $object)
    {
        $parentProduct = $this->_getParentProduct($object);
        if ($parentProduct) {
            return $this->_mapper->map($parentProduct);
        }
        return '';
    }

    /**
     * @param Varien_Object $object
     * @return bool|Varien_Object
     */
    protected function _getParentProduct(Varien_Object $object)
    {
        $parentId = $this->_connection
            ->select()
            ->from($this->_resource->getTableName('catalog_product_relation'), 'parent_id')
            ->where('child_id = ?', $object->getId())
            ->where('parent_id != ?', $object->getId())
            ->query()
            ->fetchColumn();

        if ($parentId) {
            /** @var Mage_Catalog_Model_Resource_Product_Collection $collection */
            $collection = Mage::getModel('gomage_feed/product_collection');
            if ($object->getStoreId()) {
                $collection->setStoreId($object->getStoreId());
                $collection->addStoreFilter($object->getStoreId());
            }
            $collection->addAttributeToSelect($this->getUsedAttributes())
                ->addIdFilter($parentId)
                ->setPageSize(1);
            return $collection->getFirstItem();
        }
        return false;
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        return $this->_mapper->getUsedAttributes();
    }

}