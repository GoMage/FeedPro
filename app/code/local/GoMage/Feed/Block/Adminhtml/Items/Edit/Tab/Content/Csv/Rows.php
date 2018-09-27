<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2018 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.3.0
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Block_Adminhtml_Items_Edit_Tab_Content_Csv_Rows extends Mage_Adminhtml_Block_Template
{

    public function getAddButtonHtml()
    {
        return $this->getChildHtml('add_button');
    }

    protected function _prepareLayout()
    {
        $this->setChild(
            'add_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->addData(array(
                        'id'    => 'add_new_row_button',
                        'class' => 'add',
                        'type'  => 'button',
                        'label' => Mage::helper('adminhtml')->__('Add New Row'),
                    )
                )
        );
        return parent::_prepareLayout();
    }

    public function getFeed()
    {
        if (Mage::registry('gomage_feed')) {
            return Mage::registry('gomage_feed');
        } else {
            return new Varien_Object();
        }
    }

    /**
     * @return array
     */
    public function getBaseTypes()
    {
        return Mage::getSingleton('gomage_feed/adminhtml_system_config_source_field_baseType')->toOptionArray();
    }

    /**
     * @return array
     */
    public function getExtendedTypes()
    {
        return Mage::getSingleton('gomage_feed/adminhtml_system_config_source_field_extendedType')->toOptionArray();
    }

    /**
     * @return array
     */
    public function getOutputs()
    {
        return Mage::getSingleton('gomage_feed/adminhtml_system_config_source_output')->toOptionArray();
    }

    /**
     * @return array
     */
    public function getProductAttributes()
    {
        return $this->helper('gomage_feed/attribute')->getProductAttributes(true);
    }

    /**
     * @return string
     */
    public function getRows()
    {
        $rows    = array();
        $content = $this->getFeed()->getContent();

        if ($content) {
            $rows = Zend_Json::decode($content);
            $rows = $this->_prepareRows($rows);
        }
        return Zend_Json::encode($rows);
    }

    /**
     * @param  array $rows
     * @return array
     */
    protected function _prepareRows(array $rows)
    {
        foreach ($rows as $key => $value) {
            if (is_array($value)) {
                $rows[$key] = $this->_prepareRows($value);
            }
        }
        return array_merge($rows, []);
    }

}