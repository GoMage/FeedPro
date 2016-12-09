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
class GoMage_Feed_Model_Mapper_AttributePercent extends GoMage_Feed_Model_Mapper_Attribute implements GoMage_Feed_Model_Mapper_MapperInterface
{

    /**
     * @var float
     */
    protected $_percent;

    public function __construct($arguments)
    {
        $this->_percent = floatval($arguments['percent']);
        parent::__construct($arguments['code']);
    }

    /**
     * @param  Varien_Object $object
     * @return float
     */
    public function map(Varien_Object $object)
    {
        return floatval(parent::map($object)) * $this->_percent / 100;
    }

}