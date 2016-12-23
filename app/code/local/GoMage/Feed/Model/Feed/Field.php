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
 * @version      Release: 4.0.0
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Feed_Field
{
    /**
     * @var string
     */
    protected $_type;

    /**
     * @var string
     */
    protected $_value;

    /**
     * @var GoMage_Feed_Model_Mapper_MapperInterface
     */
    protected $_mapper;


    public function __construct($arguments)
    {
        $this->_type  = $arguments['type'];
        $this->_value = $arguments['value'];

        /** @var GoMage_Feed_Model_Mapper_Factory $mapperFactory */
        $mapperFactory = Mage::getSingleton('gomage_feed/mapper_factory');

        $this->_mapper = $mapperFactory->create($this->_type, $this->_value);
    }

    /**
     * @param  Varien_Object $object
     * @return mixed
     */
    public function map(Varien_Object $object)
    {
        return $this->_mapper->map($object);
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        return $this->_mapper->getUsedAttributes();
    }

}