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
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Reader_Factory
{

    protected $_readers = array(
        'product' => 'gomage_feed/reader_product',
        'review'  => 'gomage_feed/reader_review',
    );

    /**
     * @param  string $entityType
     * @param  GoMage_Feed_Model_Reader_Params $params
     * @return GoMage_Feed_Model_Reader_ReaderInterface
     * @throws Mage_Core_Exception
     */
    public function create($entityType, $params)
    {
        if (!isset($this->_readers[$entityType])) {
            Mage::throwException(Mage::helper('gomage_feed')->__('Undefined reader.'));
        }
        return Mage::getModel($this->_readers[$entityType], $params);
    }

}
