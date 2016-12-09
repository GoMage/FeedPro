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
 * @since        Class available since Release 1.0
 */
class GoMage_Feed_Block_Adminhtml_Attributes_Edit_Tab_Data extends Mage_Adminhtml_Block_Template
{

    protected $_attributeCollection;

    /**
     * @return GoMage_Feed_Model_Attribute|Varien_Object
     */
    public function getAttribute()
    {
        if (Mage::registry('gomage_attribute')) {
            return Mage::registry('gomage_attribute');
        } else {
            return new Varien_Object();
        }
    }

    protected function getAttributeCollection()
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
     * @return array
     */
    public function getTypes()
    {
        return Mage::getSingleton('gomage_feed/adminhtml_system_config_source_field_attributeType')->toOptionArray();
    }

    /**
     * @return array
     */
    public function getOperators()
    {
        return Mage::getSingleton('gomage_feed/adminhtml_system_config_source_operator')->toOptionArray();
    }

    /**
     * @return array
     */
    public function getProductAttributes()
    {
        return $this->helper('gomage_feed/attribute')->getProductAttributes();
    }

}