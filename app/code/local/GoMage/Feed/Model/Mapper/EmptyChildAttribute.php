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
class EmptyChildAttribute extends Attribute implements GoMage_Feed_Model_Mapper_MapperInterface
{

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $_resource;

    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $_connection;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;


    public function __construct(
        $value,
        \Magento\Catalog\Api\ProductAttributeRepositoryInterface $attributeRepository,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
    ) {
        parent::__construct($value, $attributeRepository);
        $this->_resource                 = $resource;
        $this->_connection               = $resource->getConnection();
        $this->_productCollectionFactory = $productCollectionFactory;
    }

    /**
     * @param  \Magento\Framework\DataObject $object
     * @return mixed
     */
    public function map(\Magento\Framework\DataObject $object)
    {
        $result = parent::map($object);
        if (!empty($result)) {
            return $result;
        }
        $parentProduct = $this->_getParentProduct($object);
        if ($parentProduct) {
            return parent::map($parentProduct);
        }
        return '';
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @return bool|\Magento\Framework\DataObject
     */
    protected function _getParentProduct(\Magento\Framework\DataObject $object)
    {
        $parentId = $this->_connection
            ->select()
            ->from($this->_resource->getTableName('catalog_product_relation'), 'parent_id')
            ->where('child_id = ?', $object->getId())
            ->where('parent_id != ?', $object->getId())
            ->query()
            ->fetchColumn();

        if ($parentId) {
            $collection = $this->_productCollectionFactory->create();
            return $collection->addAttributeToSelect($this->_code)
                ->addIdFilter($parentId)
                ->fetchItem();
        }
        return false;
    }

}