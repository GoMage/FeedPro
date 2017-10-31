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
 * @version      Release: 4.0.0
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Writer_Xml extends GoMage_Feed_Model_Writer_AbstractWriter
{

    /**
     * @var GoMage_Feed_Model_Content_Xml
     */
    protected $_content;

    public function __construct($arguments)
    {
        parent::__construct($arguments['fileName']);
        $this->_content = $arguments['content'];
        fwrite($this->_fileHandler, $this->_content->getHeader());
    }

    /**
     * Object destructor.
     */
    public function __destruct()
    {
        fwrite($this->_fileHandler, $this->_content->getFooter());
        parent::__destruct();
    }

    /**
     * @param  array $data
     */
    public function write(array $data)
    {
        fwrite($this->_fileHandler, strtr($this->_content->getBlock(), $data));
    }
}
