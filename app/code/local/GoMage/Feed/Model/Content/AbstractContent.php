<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2019 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/license-agreement/  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.3.1
 * @since        Class available since Release 4.0.0
 */
abstract class GoMage_Feed_Model_Content_AbstractContent implements GoMage_Feed_Model_Content_ContentInterface
{
    /**
     * @var string
     */
    protected $_content;

    /**
     * @var GoMage_Feed_Model_Feed_Row_Collection
     */
    protected $_rows;

    public function __construct($arguments)
    {
        if (isset($arguments['content'])) {
            $this->_content = $arguments['content'];
        }
    }
}
