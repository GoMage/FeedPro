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
class GoMage_Feed_Model_Collection implements \Iterator
{

    /**
     * @var int
     */
    protected $_index = 0;

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
        $this->_index = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->_items[$this->_index];
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->_index;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        ++$this->_index;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return isset($this->_items[$this->_index]);
    }

    /**
     * @param mixed $value
     */
    public function add($value)
    {
        $this->_items[] = $value;
        $this->rewind();
    }

}
