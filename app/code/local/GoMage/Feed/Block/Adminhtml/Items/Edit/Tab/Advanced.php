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
 * @since        Class available since Release 1.0
 */
class GoMage_Feed_Block_Adminhtml_Items_Edit_Tab_Advanced extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * @return array
     */
    protected function _getHours()
    {
        $hours  = array();
        $locale = Mage::getSingleton('core/locale');
        for ($i = 0; $i < 24; $i++) {
            $hours[] = array('label' => sprintf('%02d:00', $i), 'value' => date('H', mktime($i, 0, 0, 1, 1, 1970) + $locale->date()->getGmtOffset()));
        }
        return $hours;
    }

    /**
     * @return array
     */
    protected function _getRestartCron()
    {
        $result = array();
        for ($i = 1; $i <= 5; $i++) {
            $result[] = array('label' => $i, 'value' => $i);
        }
        return $result;
    }

    protected function _prepareForm()
    {
        $form  = new Varien_Data_Form();
        $hours = $this->_getHours();

        if (Mage::registry('gomage_feed')) {
            $item = Mage::registry('gomage_feed');
        } else {
            $item = Mage::getModel('gomage_feed/item');
        }

        $this->setForm($form);
        $fieldset = $form->addFieldset('advanced', array('legend' => $this->__('File Creation Settings')));

        $headerBar = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
                    'label'   => $this->__('Feed Pro Help'),
                    'class'   => 'go',
                    'id'      => 'feed_pro_help',
                    'onclick' => 'window.open(\'https://wiki.gomage.com/hc/en-us/articles/115002746512-Advanced-Settings\')'
                )
            );

        $fieldset->setHeaderBar($headerBar->toHtml());

        $field = $fieldset->addField('generate_type', 'select', array(
                'name'     => 'generate_type',
                'label'    => $this->__('Generate Products'),
                'title'    => $this->__('Generate Products'),
                'required' => false,
                'values'   => Mage::getModel('gomage_feed/adminhtml_system_config_source_generate')->toOptionArray(),
            )
        );
        if (!$item->getId()) {
            $field->setValue(GoMage_Feed_Model_Adminhtml_System_Config_Source_Generate::ALL);
        }


        $field = $fieldset->addField('iteration_limit', 'text', array(
                'name'     => 'iteration_limit',
                'label'    => $this->__('Number of Products'),
                'title'    => $this->__('Number of Products'),
                'required' => true,
                'note'     => '"0" = All products. <br/>This option allows to optimize file creation for low memory servers.<br/>You have to increase php memory_limit before changing this value to maximum.',

            )
        );
        if (!$item->getId()) {
            $field->setValue('0');
        }

        $field = $fieldset->addField('use_layer', 'select', array(
                'name'     => 'use_layer',
                'label'    => $this->__('Export Out of Stock Products'),
                'title'    => $this->__('Export Out of Stock Products'),
                'required' => false,
                'values'   => Mage::getModel('gomage_feed/adminhtml_system_config_source_uselayer')->toOptionArray(),
            )
        );
        if (!$item->getId()) {
            $field->setValue(GoMage_Feed_Model_Adminhtml_System_Config_Source_Uselayer::NO);
        }

        $field = $fieldset->addField('use_disabled', 'select', array(
                'name'     => 'use_disabled',
                'label'    => $this->__('Export Disabled Products'),
                'title'    => $this->__('Export Disabled Products'),
                'required' => false,
                'values'   => array(1 => $this->__('No'), 0 => $this->__('Yes'))
            )
        );
        if (!$item->getId()) {
            $field->setValue('1');
        }

        $field = $fieldset->addField('visibility', 'select', array(
                'name'     => 'visibility',
                'label'    => $this->__('Products Visibility'),
                'title'    => $this->__('Products Visibility'),
                'required' => false,
                'values'   => Mage::getModel('gomage_feed/adminhtml_system_config_source_visibility')->toOptionArray(),
            )
        );
        if (!$item->getId()) {
            $field->setValue('4');
        }

        $fieldset = $form->addFieldset('generate_settings', array('legend' => $this->__('Auto-generate Settings')));

        $fieldset->addField('generate_status', 'select',
            array(
                'name'   => 'generate_status',
                'label'  => $this->__('Status'),
                'values' => Mage::getModel('adminhtml/system_config_source_enabledisable')->toOptionArray(),
            )
        );

        $field = $fieldset->addField('generate_day', 'multiselect', array(
                'name'     => 'generate_day',
                'label'    => $this->__('Available Days'),
                'title'    => $this->__('Available Days'),
                'required' => false,
                'values'   => Mage::getModel('gomage_feed/adminhtml_system_config_source_day')->toOptionArray(),
            )
        );

        if (!$item->getId()) {
            $field->setValue('sun,mon,tue,wed,thu,fri,sat');
        }

        $fieldset->addField('generate_hour', 'select', array(
                'name'     => 'generate_hour',
                'label'    => $this->__('Active From, hour'),
                'title'    => $this->__('Active From, hour'),
                'required' => false,
                'values'   => $hours,
            )
        );

        $fieldset->addField('generate_hour_to', 'select', array(
                'name'     => 'generate_hour_to',
                'label'    => $this->__('Active To, hour'),
                'title'    => $this->__('Active To, hour'),
                'required' => false,
                'values'   => $hours,
                'disabled' => (!$item->getId() || $item->getData('generate_interval') > 6)
            )
        );

        $field = $fieldset->addField('generate_interval', 'select', array(
                'name'     => 'generate_interval',
                'label'    => $this->__('Interval, hours'),
                'title'    => $this->__('Interval, hours'),
                'required' => false,
                'values'   => Mage::getModel('gomage_feed/adminhtml_system_config_source_interval')->toOptionArray(),
                'class'    => 'gomage-feed-validate-generate-interval'
            )
        );
        if (!$item->getId()) {
            $field->setValue('24');
        }
        $field->setOnchange('gomagefeed_setinterval(this, \'generate_hour_to\')');

        $field = $fieldset->addField('restart_cron', 'select', array(
                'name'     => 'restart_cron',
                'label'    => $this->__('Restart Cron, times'),
                'title'    => $this->__('Restart Cron, times'),
                'required' => false,
                'values'   => $this->_getRestartCron(),
            )
        );

        if (!$item->getId()) {
            $field->setValue('3');
        }

        $fieldset  = $form->addFieldset('upload_settings', array('legend' => $this->__('Auto-upload Settings')));

        $fieldset->addField('upload_status', 'select',
            array(
                'name'   => 'upload_status',
                'label'  => $this->__('Status'),
                'values' => Mage::getModel('adminhtml/system_config_source_enabledisable')->toOptionArray(),
            )
        );

        $field = $fieldset->addField('upload_day', 'multiselect', array(
                'name'     => 'upload_day',
                'label'    => $this->__('Available Days'),
                'title'    => $this->__('Available Days'),
                'required' => false,
                'values'   => Mage::getModel('gomage_feed/adminhtml_system_config_source_day')->toOptionArray(),
            )
        );

        if (!$item->getId()) {
            $field->setValue('sun,mon,tue,wed,thu,fri,sat');
        }

        $fieldset->addField('upload_hour', 'select', array(
                'name'     => 'upload_hour',
                'label'    => $this->__('Active From, hour'),
                'title'    => $this->__('Active From, hour'),
                'required' => false,
                'values'   => $hours
            )
        );

        $fieldset->addField('upload_hour_to', 'select', array(
                'name'     => 'upload_hour_to',
                'label'    => $this->__('Active To, hour'),
                'title'    => $this->__('Active To, hour'),
                'required' => false,
                'values'   => $hours,
                'disabled' => (!$item->getId() || $item->getData('upload_interval') > 6)
            )
        );

        $field = $fieldset->addField('upload_interval', 'select', array(
                'name'     => 'upload_interval',
                'label'    => $this->__('Interval, hours'),
                'title'    => $this->__('Interval, hours'),
                'required' => false,
                'values'   => Mage::getModel('gomage_feed/adminhtml_system_config_source_interval')->toOptionArray(),
                'class'    => 'gomage-feed-validate-upload-interval'
            )
        );

        if (!$item->getId()) {
            $field->setValue('24');
        }
        $field->setOnchange('gomagefeed_setinterval(this, \'upload_hour_to\')');

        if ($item->getId()) {
            $form->setValues($item->getData());
        }

        return parent::_prepareForm();
    }


}