<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2019 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.3.1
 * @since        Class available since Release 1.0
 */
class GoMage_Feed_Block_Adminhtml_Items_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('gomage_feed_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle($this->__('Feed Information'));
    }

    protected function _prepareLayout()
    {
        if (($type = $this->getRequest()->getParam('type', null)) || (Mage::registry('gomage_feed') && ($type = Mage::registry('gomage_feed')->getType()))) {
            $this->addTab('main_section', array(
                    'label'   => $this->__('Item information'),
                    'title'   => $this->__('Item information'),
                    'content' => $this->getLayout()->createBlock('gomage_feed/adminhtml_items_edit_tab_main')->toHtml(),
                )
            );
            if ($type == 'csv') {
                $this->addTab('content_section', array(
                        'label'   => $this->__('Content Settings'),
                        'title'   => $this->__('Content Settings'),
                        'content' => $this->getLayout()->createBlock('gomage_feed/adminhtml_items_edit_tab_content_csv')
                            ->setTemplate('gomage/feed/item/edit/content.phtml')->toHtml(),
                    )
                );
            } else {
                $this->addTab('content_section', array(
                        'label'   => $this->__('Content Settings'),
                        'title'   => $this->__('Content Settings'),
                        'content' => $this->getLayout()->createBlock('gomage_feed/adminhtml_items_edit_tab_content_xml')->toHtml(),
                    )
                );
            }
            $this->addTab('conditions_section', array(
                    'label'   => $this->__('Conditions'),
                    'title'   => $this->__('Conditions'),
                    'content' => $this->getLayout()->createBlock('gomage_feed/adminhtml_items_edit_tab_conditions')->toHtml(),
                )
            );
            $this->addTab('ftp_section', array(
                    'label'   => $this->__('FTP Settings'),
                    'title'   => $this->__('FTP Settings'),
                    'content' => $this->getLayout()->createBlock('gomage_feed/adminhtml_items_edit_tab_ftp')->toHtml(),
                )
            );
            $this->addTab('advanced', array(
                    'label'   => $this->__('Advanced Settings'),
                    'title'   => $this->__('Advanced Settings'),
                    'content' => $this->getLayout()->createBlock('gomage_feed/adminhtml_items_edit_tab_advanced')->toHtml(),
                )
            );

        } else {
            $this->addTab('main_section', array(
                    'label'   => $this->__('Content Settings'),
                    'title'   => $this->__('Content Settings'),
                    'content' => $this->getLayout()->createBlock('gomage_feed/adminhtml_items_edit_tab_type')->toHtml(),
                )
            );
        }
        if ($tabId = htmlspecialchars($this->getRequest()->getParam('tab'))) {
            $this->setActiveTab($tabId);
        }
        return parent::_beforeToHtml();
    }

}