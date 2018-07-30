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
class GoMage_Feed_Model_Mapper_Factory
{
    /**
     * @var array
     */
    protected $_customMappers = array(
        'entity_id'            => 'gomage_feed/mapper_custom_productId',
        'is_in_stock'          => 'gomage_feed/mapper_custom_stock',
        'qty'                  => 'gomage_feed/mapper_custom_qty',
        'category'             => 'gomage_feed/mapper_custom_category',
        'category_id'          => 'gomage_feed/mapper_custom_categoryId',
        'final_price'          => 'gomage_feed/mapper_custom_finalPrice',
        'product_type'         => 'gomage_feed/mapper_custom_productType',
        'url'                  => 'gomage_feed/mapper_custom_productUrl',
        'category_subcategory' => 'gomage_feed/mapper_custom_categorySubcategory',
        'image'                => 'gomage_feed/mapper_custom_image',
        'image_2'              => 'gomage_feed/mapper_custom_image2',
        'image_3'              => 'gomage_feed/mapper_custom_image3',
        'image_4'              => 'gomage_feed/mapper_custom_image4',
        'image_5'              => 'gomage_feed/mapper_custom_image5',
    );

    /**
     * @param  string $type
     * @param  string $value
     * @return GoMage_Feed_Model_Mapper_MapperInterface
     */
    public function create($type, $value)
    {
        $className = $this->_getCustomMapper($value);

        if ($className && !in_array($type, array(GoMage_Feed_Model_Adminhtml_System_Config_Source_Field_TypeInterface::PARENT_ATTRIBUTE,
                    GoMage_Feed_Model_Adminhtml_System_Config_Source_Field_TypeInterface::EMPTY_PARENT_ATTRIBUTE,
                    GoMage_Feed_Model_Adminhtml_System_Config_Source_Field_TypeInterface::EMPTY_CHILD_ATTRIBUTE)
            )) {
            return Mage::getModel($className, $value);
        }

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
            case GoMage_Feed_Model_Adminhtml_System_Config_Source_Field_TypeInterface::REVIEW:
                $className = 'gomage_feed/mapper_review';
                break;
        }

        return Mage::getModel($className, $value);
    }

    /**
     * @param  string $value
     * @return bool|string
     */
    protected function _getCustomMapper($value)
    {
        if (is_string($value) && isset($this->_customMappers[$value])) {
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
