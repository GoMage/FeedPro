<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2017 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/license-agreement/  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.2.0
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Mapper_AttributeSet implements GoMage_Feed_Model_Mapper_MapperInterface
{

    /**
     * @var GoMage_Feed_Model_Collection
     */
    protected $_fields;

    public function __construct($value)
    {
        $this->_fields = Mage::getModel('gomage_feed/collection');

        foreach ($value as $data) {
            $field = Mage::getModel('gomage_feed/attribute_field', array(
                    'type'   => GoMage_Feed_Model_Adminhtml_System_Config_Source_Field_TypeInterface::ATTRIBUTE,
                    'value'  => $data['code'],
                    'prefix' => $data['prefix']
                )
            );
            $this->_fields->add($field);
        }
    }

    /**
     * @param  Varien_Object $object
     * @return mixed
     */
    public function map(Varien_Object $object)
    {
        $values = array_map(function (GoMage_Feed_Model_Attribute_Field $field) use ($object) {
            return $field->map($object);
        }, iterator_to_array($this->_fields)
        );

        return implode('', $values);
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        $attributes = [];
        /** @var GoMage_Feed_Model_Attribute_Field $field */
        foreach ($this->_fields as $field) {
            $attributes = array_merge($attributes, $field->getUsedAttributes());
        }
        return $attributes;
    }
}