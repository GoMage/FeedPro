<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2017 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.1.0
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Block_Adminhtml_Items_Edit_Tab_Conditions
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Prepare content for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('catalogrule')->__('Conditions');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('catalogrule')->__('Conditions');
    }

    /**
     * Returns status flag about this tab can be showen or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
    }

    protected function _prepareForm()
    {
        if (Mage::registry('gomage_feed')) {
            $model = Mage::registry('gomage_feed');
        } else {
            $model = new Varien_Object();
        }

        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('rule_');

        $renderer = Mage::getBlockSingleton('adminhtml/widget_form_renderer_fieldset')
            ->setTemplate('gomage/feed/fieldset.phtml')
            ->setNewChildUrl($this->getUrl('*/promo_catalog/newConditionHtml/form/rule_conditions_fieldset'));

        $fieldset = $form->addFieldset('conditions_fieldset', array(
                'legend' => Mage::helper('catalogrule')->__('Conditions (leave blank for all products)'))
        )->setRenderer($renderer);
        $headerBar = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
                    'label'   => $this->__('Feed Pro Help'),
                    'class'   => 'go',
                    'id'      => 'feed_pro_help_4',
                    'onclick' => 'window.open(\'https://wiki.gomage.com/hc/en-us/articles/115002749991-Conditions\')'
                )
            );
        $fieldset->setHeaderBar($headerBar->toHtml());
        $fieldset->addField('conditions', 'text', array(
                'name'     => 'conditions',
                'label'    => Mage::helper('catalogrule')->__('Conditions'),
                'title'    => Mage::helper('catalogrule')->__('Conditions'),
                'required' => true,
            )
        )->setRule($model)->setRenderer(Mage::getBlockSingleton('rule/conditions'));

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
