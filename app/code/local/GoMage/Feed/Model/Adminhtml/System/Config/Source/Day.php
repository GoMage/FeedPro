<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2020 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.3.2
 * @since        Class available since Release 3.0
 */
class GoMage_Feed_Model_Adminhtml_System_Config_Source_Day
{

    public function toOptionArray()
    {
        $helper = Mage::helper('gomage_feed');

        return array(
            array('label' => $helper->__('Sunday'), 'value' => 'sun'),
            array('label' => $helper->__('Monday'), 'value' => 'mon'),
            array('label' => $helper->__('Tuesday'), 'value' => 'tue'),
            array('label' => $helper->__('Wednesday'), 'value' => 'wed'),
            array('label' => $helper->__('Thursday'), 'value' => 'thu'),
            array('label' => $helper->__('Friday'), 'value' => 'fri'),
            array('label' => $helper->__('Saturday'), 'value' => 'sat'),
        );
    }

}