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
class GoMage_Feed_Model_Mapper_StaticValue implements GoMage_Feed_Model_Mapper_MapperInterface
{

    /**
     * @var string
     */
    protected $_value;

    public function __construct($value)
    {
        $this->_value = $value;
    }

    /**
     * @param  Varien_Object
     * @return mixed
     */
    public function map(Varien_Object $object)
    {
        return $this->_value;
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        return array();
    }
}