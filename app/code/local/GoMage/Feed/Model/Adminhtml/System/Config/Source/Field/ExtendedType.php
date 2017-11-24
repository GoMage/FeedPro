<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2017 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.1.0
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Adminhtml_System_Config_Source_Field_ExtendedType extends GoMage_Feed_Model_Adminhtml_System_Config_Source_Field_BaseType
{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $helper = Mage::helper('gomage_feed');
        return array_merge(parent::toOptionArray(), array(
                array('value' => self::EMPTY_PARENT_ATTRIBUTE, 'label' => $helper->__('If Parent attr. is empty')),
                array('value' => self::EMPTY_CHILD_ATTRIBUTE, 'label' => $helper->__('If Child attr. is empty')))
        );
    }

}
