<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2016 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 3.7.0
 * @since        Class available since Release 3.4
 */
class GoMage_Feed_Helper_Attribute extends Mage_Core_Helper_Abstract
{
    /**
     * @var Mage_Eav_Model_Resource_Entity_Attribute_Collection
     */
    protected $_attributeCollection;

    /**
     * @return Mage_Eav_Model_Resource_Entity_Attribute_Collection
     */
    public function getAttributeCollection()
    {
        if (is_null($this->_attributeCollection)) {
            $this->_attributeCollection = Mage::getResourceModel('eav/entity_attribute_collection')
                ->setItemObjectClass('catalog/resource_eav_attribute')
                ->setEntityTypeFilter(Mage::getResourceModel('catalog/product')->getTypeId())
                ->addFieldToFilter('attribute_code', array('nin' => array('gallery', 'media_gallery')));
        }
        return $this->_attributeCollection;
    }

    /**
     * @param  bool $add_dynamic_attributes
     * @return array
     */
    public function getProductAttributes($add_dynamic_attributes = false)
    {
        $attributes = $this->getAttributeCollection()->getItems();

        $attributes = array_filter($attributes, function ($attribute) {
            return (bool)$attribute->getFrontendLabel();
        }
        );

        $attributes = array_map(function ($attribute) {
            return array(
                'value' => $attribute->getAttributeCode(),
                'label' => $attribute->getFrontendLabel(),
            );
        }, $attributes
        );

        /** @var GoMage_Feed_Model_Mapper_Factory $mapperFactory */
        $mapperFactory = Mage::getSingleton('gomage_feed/mapper_factory');

        foreach ($mapperFactory->getCustomMappers() as $value => $class) {
            /** @var GoMage_Feed_Model_Mapper_Custom_CustomMapperInterface $model */
            $model        = Mage::getModel($class);
            $attributes[] = array(
                'value' => $value,
                'label' => $model::getLabel()
            );
        }

        usort($attributes, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        }
        );

        if ($add_dynamic_attributes) {
            $dynamic_attributes = Mage::getResourceModel('gomage_feed/attribute_collection');
            foreach ($dynamic_attributes as $attribute) {
                array_unshift($attributes, array(
                        'value' => 'custom:' . $attribute->getCode(),
                        'label' => '* ' . $attribute->getName())
                );
            }
        }

        return $attributes;
    }

}
