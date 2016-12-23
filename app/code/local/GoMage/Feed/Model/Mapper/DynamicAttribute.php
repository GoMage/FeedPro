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
class GoMage_Feed_Model_Mapper_DynamicAttribute implements GoMage_Feed_Model_Mapper_MapperInterface
{
    /**
     * @var GoMage_Feed_Model_Feed_Field
     */
    protected $_default;

    /**
     * @var GoMage_Feed_Model_Collection
     */
    protected $_rows;

    public function __construct($value)
    {
        /** @var GoMage_Feed_Model_Attribute $attribute */
        $attribute = Mage::getModel('gomage_feed/attribute')->load($value, 'code');

        $_defaultType  = $attribute->getDefaultType() ?: GoMage_Feed_Model_Adminhtml_System_Config_Source_Field_TypeInterface::ATTRIBUTE;
        $_defaultValue = $attribute->getDefaultValue() ?: '';
        if (!$_defaultValue) {
            $_defaultType = GoMage_Feed_Model_Adminhtml_System_Config_Source_Field_TypeInterface::STATIC_VALUE;
        }
        $this->_default = Mage::getModel('gomage_feed/feed_field',
            array(
                'type'  => $_defaultType,
                'value' => $_defaultValue
            )
        );

        $this->_rows = Mage::getModel('gomage_feed/collection');
        $content = Zend_Json::decode($attribute->getData('data'));

        foreach ($content as $data) {
            /** @var GoMage_Feed_Model_Attribute_Row_Data $rowData */
            $rowData = Mage::getModel('gomage_feed/attribute_row_data', $data);

            /** @var GoMage_Feed_Model_Attribute_Row $row */
            $row = Mage::getModel('gomage_feed/attribute_row', $rowData);

            $this->_rows->add($row);
        }
    }

    /**
     * @param  Varien_Object $object
     * @return mixed
     */
    public function map(Varien_Object $object)
    {
        /** @var GoMage_Feed_Model_Attribute_Row $row */
        foreach ($this->_rows as $row) {
            if ($row->verify($object)) {
                return $row->map($object);
            }
        }
        return $this->_default->map($object);
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        $attributes = [];
        /** @var GoMage_Feed_Model_Attribute_Row $row */
        foreach ($this->_rows as $row) {
            $attributes = array_merge($attributes, $row->getUsedAttributes());
        }
        return array_merge($attributes, $this->_default->getUsedAttributes());
    }

}