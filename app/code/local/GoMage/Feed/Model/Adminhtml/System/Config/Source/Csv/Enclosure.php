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
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Adminhtml_System_Config_Source_Csv_Enclosure
{

    const QUOTE = 1;
    const DOUBLE_QUOTE = 2;
    const SPACE = 3;
    const SINGLE_SPACE = 4;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $helper = Mage::helper('gomage_feed');
        return array(
            array('value' => self::DOUBLE_QUOTE, 'label' => $helper->__('"')),
            array('value' => self::QUOTE, 'label' => $helper->__("'")),
            array('value' => self::SPACE, 'label' => $helper->__('space - CSV format')),
            array('value' => self::SINGLE_SPACE, 'label' => $helper->__('space - without double space')),
        );
    }

    /**
     * @param int $enclosure
     * @return string
     */
    public function getSymbol($enclosure)
    {
        $symbol = '"';
        switch ((int)$enclosure) {
            case (self::QUOTE):
                $symbol = '"';
                break;
            case (self::SPACE):
                $symbol = ' ';
                break;
            case (self::SINGLE_SPACE):
                $symbol = '';
                break;
            case (self::DOUBLE_QUOTE):
                $symbol = '"';
                break;
        }
        return $symbol;
    }

}
