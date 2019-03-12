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
class GoMage_Feed_Model_Mapper_ConfigurableValue implements GoMage_Feed_Model_Mapper_MapperInterface
{

    /**
     * @var string
     */
    protected $_prefix;

    /**
     * @var string
     */
    protected $_code;

    /**
     * @var Mage_Eav_Model_Entity_Attribute
     */
    protected $_attribute;


    public function __construct($value)
    {
        $this->_prefix    = $value['prefix'];
        $this->_code      = $value['code'];
        $this->_attribute = Mage::getModel('eav/entity_attribute')->loadByCode('catalog_product', $this->_code);
    }

    /**
     * @param  Varien_Object $object
     * @return string
     */
    public function map(Varien_Object $object)
    {
        if ($object->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) {
            $options = Mage::getResourceSingleton('catalog/product_type_configurable')
                ->getConfigurableOptions($object, array($this->_attribute));

            $options = reset($options);
            $code    = $this->_code;
            $options = array_filter($options, function ($option) use ($code) {
                return $option['attribute_code'] == $code;
            }
            );

            $options = array_map(function ($option) {
                return $option['option_title'];
            }, $options
            );
            $value   = implode(',', array_unique(array_filter($options)));
            return $value ? $this->_prefix . $value : '';
        }
        return '';
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        return array();
    }
}