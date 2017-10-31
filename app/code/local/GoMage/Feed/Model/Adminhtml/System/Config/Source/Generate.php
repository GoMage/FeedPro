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
class GoMage_Feed_Model_Adminhtml_System_Config_Source_Generate
{

    const ALL = 0;
    const CHANGED = 1;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $helper = Mage::helper('gomage_feed');
        return array(
            array('value' => self::ALL, 'label' => $helper->__('All')),
            array('value' => self::CHANGED, 'label' => $helper->__('Only changed')),
        );
    }

    /**
     * @return array
     */
    public static function toOptionHash()
    {
        $helper = Mage::helper('gomage_feed');
        return array(
            self::ALL     => $helper->__('All'),
            self::CHANGED => $helper->__('Only changed'),
        );
    }

}