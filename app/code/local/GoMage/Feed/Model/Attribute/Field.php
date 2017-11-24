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
 * @version      Release: 4.1.0
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Attribute_Field extends GoMage_Feed_Model_Feed_Field
{

    /**
     * @var string
     */
    protected $_prefix;

    public function __construct($arguments)
    {
        $this->_prefix = $arguments['prefix'];
        parent::__construct($arguments);
    }

    /**
     * @param  Varien_Object $object
     * @return mixed
     */
    public function map(Varien_Object $object)
    {
        $value = parent::map($object);
        return $value ? $this->_prefix . $value : '';
    }

}