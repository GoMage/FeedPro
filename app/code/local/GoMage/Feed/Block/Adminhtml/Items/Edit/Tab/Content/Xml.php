<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2020 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.3.2
 * @since        Class available since Release 1.0
 */
class GoMage_Feed_Block_Adminhtml_Items_Edit_Tab_Content_Xml extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();

        if (Mage::registry('gomage_feed')) {
            $item = Mage::registry('gomage_feed');
        } else {
            $item = new Varien_Object();
        }

        $this->setForm($form);
        $fieldset = $form->addFieldset('main_fieldset', array('legend' => $this->__('Content Settings')));

        $headerBar = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
                    'label'   => Mage::helper('catalog')->__('Feed Pro Help'),
                    'class'   => 'go',
                    'id'      => 'feed_pro_help',
                    'onclick' => 'window.open(\'https://wiki.gomage.com/hc/en-us/articles/115002745732-Content-Settings-for-XML\')'
                )
            );

        $fieldset->setHeaderBar(
            $headerBar->toHtml()
        );

        $fieldset->addField('content', 'textarea', array(
                'name'     => 'content',
                'label'    => $this->__('Content'),
                'title'    => $this->__('Content'),
                'required' => true,
                'style'    => 'width:500px;height:250px;'
            )
        );

        $form->setValues($item->getData());
        return parent::_prepareForm();
    }


}