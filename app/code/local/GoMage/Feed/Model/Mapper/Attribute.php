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
class GoMage_Feed_Model_Mapper_Attribute implements GoMage_Feed_Model_Mapper_MapperInterface
{
    /**
     * @var string
     */
    protected $_code;

    /**
     * @var Mage_Eav_Model_Entity_Attribute
     */
    protected $_attribute;

    public function __construct($code)
    {
        $this->_code      = $code;
        $this->_attribute = Mage::getModel('eav/entity_attribute')->loadByCode('catalog_product', $this->_code);
    }

    /**
     * @param  Varien_Object $object
     * @return mixed
     */
    public function map(Varien_Object $object)
    {
        if ($resource = $object->getResource()) {
            $this->_attribute->setEntity($resource);
        }

        return $this->_attribute->getFrontend()->getValue($object);
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        return array($this->_code);
    }
}
