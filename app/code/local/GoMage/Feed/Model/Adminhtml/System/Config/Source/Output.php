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
 * @version      Release: 4.0.0
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Adminhtml_System_Config_Source_Output
{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $helper = Mage::helper('gomage_feed');
        return array(
            array('value' => GoMage_Feed_Model_Output_OutputInterface::NONE, 'label' => $helper->__('None')),
            array('value' => GoMage_Feed_Model_Output_OutputInterface::INTEGER, 'label' => $helper->__('Integer')),
            array('value' => GoMage_Feed_Model_Output_OutputInterface::FLOATS, 'label' => $helper->__('Float')),
            array('value' => GoMage_Feed_Model_Output_OutputInterface::STRIP_TAGS, 'label' => $helper->__('Strip Tags')),
            array('value' => GoMage_Feed_Model_Output_OutputInterface::SPECIAL_ENCODE, 'label' => $helper->__('Encode special chars')),
            array('value' => GoMage_Feed_Model_Output_OutputInterface::SPECIAL_DECODE, 'label' => $helper->__('Decode special chars')),
            array('value' => GoMage_Feed_Model_Output_OutputInterface::DELETE_SPACE, 'label' => $helper->__('Delete Space')),
            array('value' => GoMage_Feed_Model_Output_OutputInterface::BIG_TO_SMALL, 'label' => $helper->__('Big to small')),
            array('value' => GoMage_Feed_Model_Output_OutputInterface::DATE_TIME, 'label' => $helper->__('DateTime')),
        );
    }

}
