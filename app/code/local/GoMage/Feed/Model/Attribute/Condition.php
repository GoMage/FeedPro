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
class GoMage_Feed_Model_Attribute_Condition
{

    /**
     * @var GoMage_Feed_Model_Feed_Field
     */
    protected $_field;

    /**
     * @var GoMage_Feed_Model_Operator_OperatorInterface
     */
    protected $_operator;

    /**
     * @var string
     */
    protected $_value;

    public function __construct(GoMage_Feed_Model_Attribute_Condition_Data $conditionData)
    {
        $this->_operator = Mage::getSingleton('gomage_feed/operator_factory')->get($conditionData->getOperator());
        $this->_field = Mage::getModel('gomage_feed/feed_field',
            array(
                'type' => GoMage_Feed_Model_Adminhtml_System_Config_Source_Field_TypeInterface::ATTRIBUTE,
                'value' => $conditionData->getCode()
            )
        );

        $attributeOptions = Mage::getModel('eav/config')->getAttribute(
            Mage_Catalog_Model_Product::ENTITY,
            $conditionData->getCode()
        )->getSource()->getAllOptions();

        $value = null;
        foreach ($attributeOptions as $attributeOption) {
            if ($attributeOption['value'] == $conditionData->getValue()) {
                $value = $attributeOption['label'];
                break;
            }
        }

        $this->_value = $value;
    }

    /**
     * @param Varien_Object $object
     * @return bool
     */
    public function verify(Varien_Object $object)
    {
        $testable = $this->_field->map($object);
        return $this->_operator->compare($testable, $this->_value);
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        return $this->_field->getUsedAttributes();
    }


}
