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
 * @version      Release: 4.0.0
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Attribute_Row
{

    /**
     * @var GoMage_Feed_Model_Collection
     */
    protected $_conditions;

    /**
     * @var GoMage_Feed_Model_Feed_Field
     */
    protected $_field;

    public function __construct(GoMage_Feed_Model_Attribute_Row_Data $rowData)
    {
        $this->_conditions = Mage::getModel('gomage_feed/collection');

        foreach ($rowData->getConditions() as $data) {
            $conditionData = Mage::getModel('gomage_feed/attribute_condition_data', $data);
            $condition     = Mage::getModel('gomage_feed/attribute_condition', $conditionData);
            $this->_conditions->add($condition);
        }

        $this->_field = Mage::getModel('gomage_feed/feed_field',
            array(
                'type'  => $rowData->getType(),
                'value' => $rowData->getValue()
            )
        );

    }

    /**
     * @param Varien_Object
     * @return bool
     */
    public function verify(Varien_Object $object)
    {
        foreach ($this->_conditions as $condition) {
            if (!$condition->verify($object)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param Varien_Object $object
     * @return mixed
     */
    public function map(Varien_Object $object)
    {
        return $this->_field->map($object);
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        $attributes = [];
        /** @var GoMage_Feed_Model_Attribute_Condition $condition */
        foreach ($this->_conditions as $condition) {
            $attributes = array_merge($attributes, $condition->getUsedAttributes());
        }
        return array_merge($attributes, $this->_field->getUsedAttributes());
    }


}
