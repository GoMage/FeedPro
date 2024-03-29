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
class GoMage_Feed_Model_Adminhtml_System_Config_Source_Interval
{

    public function toOptionArray()
    {
        $helper = Mage::helper('gomage_feed');

        return array(
            array('label' => $helper->__('every 1 hour'), 'value' => 1),
            array('label' => $helper->__('every 3 hours'), 'value' => 3),
            array('label' => $helper->__('every 6 hours'), 'value' => 6),
            array('label' => $helper->__('every 12 hours'), 'value' => 12),
            array('label' => $helper->__('every 24 hours'), 'value' => 24),
        );
    }

}