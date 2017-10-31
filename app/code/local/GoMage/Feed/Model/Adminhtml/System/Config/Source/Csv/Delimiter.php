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
class GoMage_Feed_Model_Adminhtml_System_Config_Source_Csv_Delimiter
{
    const COMMA = 'comma';
    const TAB = 'tab';
    const COLON = 'colon';
    const SPACE = 'space';
    const VERTICAL_PIPE = 'vertical pipe';
    const SEMI_COLON = 'semi-colon';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $helper = Mage::helper('gomage_feed');
        return array(
            array('value' => self::COMMA, 'label' => $helper->__('Comma')),
            array('value' => self::TAB, 'label' => $helper->__('Tab')),
            array('value' => self::COLON, 'label' => $helper->__('Colon')),
            array('value' => self::SPACE, 'label' => $helper->__('Space')),
            array('value' => self::VERTICAL_PIPE, 'label' => $helper->__('Vertical pipe')),
            array('value' => self::SEMI_COLON, 'label' => $helper->__('Semi-colon')),
        );
    }

    /**
     * @param int $delimiter
     * @return string
     */
    public function getSymbol($delimiter)
    {
        $symbol = ",";
        switch ($delimiter) {
            case (self::TAB):
                $symbol = "\t";
                break;
            case (self::COLON):
                $symbol = ":";
                break;
            case (self::SPACE):
                $symbol = " ";
                break;
            case (self::VERTICAL_PIPE):
                $symbol = "|";
                break;
            case (self::SEMI_COLON):
                $symbol = ";";
                break;
            case (self::COMMA):
                $symbol = ",";
                break;
        }
        return $symbol;
    }

}
