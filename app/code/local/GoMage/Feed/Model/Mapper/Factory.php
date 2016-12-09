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
class GoMage_Feed_Model_Mapper_Factory
{
    /**
     * @var array
     */
    protected $_customMappers;

    public function __construct($customMappers = array())
    {
        $this->_customMappers = $customMappers;
    }

    /**
     * @param  string $type
     * @param  string $value
     * @return GoMage_Feed_Model_Mapper_MapperInterface
     */
    public function create($type, $value)
    {
        $className = $this->_getCustomMapper($value);
        if (!$className) {
            switch ($type) {
                case GoMage_Feed_Model_Adminhtml_System_Config_Source_Field_TypeInterface::ATTRIBUTE:
                    $className = 'gomage_feed/mapper_attribute';
                    if (strpos($value, 'custom:') === 0) {
                        $value     = trim(str_replace('custom:', '', $value));
                        $className = 'gomage_feed/mapper_dynamicAttribute';
                    }
                    break;
                case GoMage_Feed_Model_Adminhtml_System_Config_Source_Field_TypeInterface::PARENT_ATTRIBUTE:
                    $className = 'gomage_feed/mapper_parentAttribute';
                    break;
                case GoMage_Feed_Model_Adminhtml_System_Config_Source_Field_TypeInterface::EMPTY_PARENT_ATTRIBUTE:
                    $className = 'gomage_feed/mapper_emptyParentAttribute';
                    break;
                case GoMage_Feed_Model_Adminhtml_System_Config_Source_Field_TypeInterface::EMPTY_CHILD_ATTRIBUTE:
                    $className = 'gomage_feed/mapper_emptyChildAttribute';
                    break;
                case GoMage_Feed_Model_Adminhtml_System_Config_Source_Field_TypeInterface::STATIC_VALUE:
                    $className = 'gomage_feed/mapper_staticValue';
                    break;
                case GoMage_Feed_Model_Adminhtml_System_Config_Source_Field_TypeInterface::PERCENT:
                    $className = 'gomage_feed/mapper_attributePercent';
                    break;
                case GoMage_Feed_Model_Adminhtml_System_Config_Source_Field_TypeInterface::ATTRIBUTE_SET:
                    $className = 'gomage_feed/mapper_attributeSet';
                    break;
                case GoMage_Feed_Model_Adminhtml_System_Config_Source_Field_TypeInterface::CONFIGURABLE_VALUES:
                    $className = 'gomage_feed/mapper_configurableValue';
                    break;
            }
        }
        return Mage::getModel($className, $value);
    }

    /**
     * @param  string $value
     * @return bool|string
     */
    protected function _getCustomMapper($value)
    {
        if (isset($this->_customMappers[$value])) {
            return $this->_customMappers[$value];
        }
        return false;
    }

    /**
     * @return array
     */
    public function getCustomMappers()
    {
        return $this->_customMappers;
    }

}
