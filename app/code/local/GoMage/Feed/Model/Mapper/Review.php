<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2019 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/license-agreement/  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.3.1
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Mapper_Review implements GoMage_Feed_Model_Mapper_MapperInterface
{
    /**
     * @var string
     */
    protected $_code;

    /**
     * @var GoMage_Feed_Model_Mapper_MapperInterface
     */
    protected $_mapper;

    public function __construct($code)
    {
        $this->_code = $code;
        if (strpos($this->_code, 'product:') === 0) {
            $value = trim(str_replace('product:', '', $this->_code));
            /** @var GoMage_Feed_Model_Mapper_Factory $mapperFactory */
            $mapperFactory = Mage::getSingleton('gomage_feed/mapper_factory');
            $this->_mapper = $mapperFactory->create(GoMage_Feed_Model_Adminhtml_System_Config_Source_Field_TypeInterface::ATTRIBUTE, $value);
        }
    }

    /**
     * @param  Varien_Object $object
     * @return mixed
     */
    public function map(Varien_Object $object)
    {
        if (!is_null($this->_mapper)) {
            $product = $object->getProduct() ?: Mage::getModel('catalog/product')->load($object->getEntityPkValue());
            $object->setProduct($product);
            return $this->_mapper->map($product);
        }
        if ($this->_code == 'summary') {
            $product = $object->getProduct() ?: Mage::getModel('catalog/product')->load($object->getEntityPkValue());
            $object->getEntitySummary($product, $object->getStoreId());
            $object->setProduct($product);
            return $product->getRatingSummary()->getRatingSummary();
        }
        $method = 'get' . uc_words($this->_code, '');
        return $object->$method() ?: $object->getData($this->_code);
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        return array();
    }
}