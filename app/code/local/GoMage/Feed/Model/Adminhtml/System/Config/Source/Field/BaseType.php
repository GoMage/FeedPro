<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2016 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.0.0
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Adminhtml_System_Config_Source_Field_BaseType implements GoMage_Feed_Model_Adminhtml_System_Config_Source_Field_TypeInterface
{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $helper = Mage::helper('gomage_feed');
        return array(
            array('value' => self::ATTRIBUTE, 'label' => $helper->__('Attribute')),
            array('value' => self::PARENT_ATTRIBUTE, 'label' => $helper->__('Parent Attribute')),
            array('value' => self::STATIC_VALUE, 'label' => $helper->__('Static Value')),
        );
    }

}
