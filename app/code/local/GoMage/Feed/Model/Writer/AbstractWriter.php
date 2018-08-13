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
abstract class GoMage_Feed_Model_Writer_AbstractWriter implements GoMage_Feed_Model_Writer_WriterInterface
{
    /**
     * @var string
     */
    protected $_fileName;

    /**
     * @var resource
     */
    protected $_fileHandler;


    public function __construct($fileName)
    {
        $this->_fileName    = $fileName;
        $destination        = Mage::helper('gomage_feed/generator')->getBaseDir() . DS . $this->_fileName;
        $this->_fileHandler = fopen($destination, 'w');
    }

    /**
     * Object destructor.
     */
    public function __destruct()
    {
        if (is_resource($this->_fileHandler)) {
            fclose($this->_fileHandler);
        }
    }

    /**
     * @param  array $data
     */
    abstract public function write(array $data);
}