<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2016 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/license-agreement/  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 3.7.0
 * @since        Class available since Release 1.0
 */
class GoMage_Feed_Model_Writer_Factory
{
    protected $_writers = array(
        'csv' => 'gomage_feed/writer_csv',
        'xml' => 'gomage_feed/writer_xml'
    );

    /**
     * @param  string $type
     * @param  array $arguments
     * @return GoMage_Feed_Model_Writer_WriterInterface
     * @throws Mage_Core_Exception
     */
    public function create($type, array $arguments = array())
    {
        if (!isset($this->_writers[$type])) {
            Mage::throwException(Mage::helper('gomage_feed')->__('Undefined writer.'));
        }
        return Mage::getModel($this->_writers[$type], $arguments);
    }

}
