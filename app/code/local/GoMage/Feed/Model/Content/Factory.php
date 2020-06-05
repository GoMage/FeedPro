<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2020 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/license-agreement/  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.3.2
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Content_Factory
{
    protected $_contents = [
        'csv' => 'gomage_feed/content_csv',
        'xml' => 'gomage_feed/content_xml'
    ];

    /**
     * @param string $type
     * @param array $arguments
     * @return GoMage_Feed_Model_Content_ContentInterface
     * @throws Mage_Core_Exception
     */
    public function create($type, array $arguments = array())
    {
        if (!isset($this->_contents[$type])) {
            Mage::throwException(Mage::helper('gomage_feed')->__('Undefined content type.'));
        }
        return Mage::getModel($this->_contents[$type], $arguments);
    }

}
