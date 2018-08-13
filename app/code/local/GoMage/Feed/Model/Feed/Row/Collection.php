<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2017 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/license-agreement/  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.2.0
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Feed_Row_Collection implements \Iterator
{

    /**
     * @var array
     */
    protected $_items = array();

    public function __construct()
    {
        $this->rewind();
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        reset($this->_items);
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return current($this->_items);
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return key($this->_items);
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        next($this->_items);
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return key($this->_items) !== null;
    }

    /**
     * @param  GoMage_Feed_Model_Feed_Row $row
     * @throws Mage_Core_Exception
     */
    public function add(GoMage_Feed_Model_Feed_Row $row)
    {
        if (isset($this->_items[$row->getName()])) {
            Mage::throwException(Mage::helper('gomage_feed')->__('Duplicate row.'));
        }
        $this->_items[$row->getName()] = $row;
    }

    /**
     * @param  Varien_Object $object
     * @return array
     */
    public function calc(Varien_Object $object)
    {
        return array_map(function (GoMage_Feed_Model_Feed_Row $row) use ($object) {
            return $row->map($object);
        }, $this->_items
        );
    }

    public function getAttributes()
    {
        $attributes = array();
        /** @var GoMage_Feed_Model_Feed_Row $row */
        foreach ($this->_items as $row) {
            $attributes = array_merge($attributes, $row->getUsedAttributes());
        }
        return array_unique($attributes);
    }

}
