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
class GoMage_Feed_Block_Adminhtml_Items_Mappingexportftp_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {

        parent::__construct();
        $this->setId('gomage_feed_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle($this->__('Export'));

    }

    protected function _prepareLayout()
    {

        $this->addTab('filter_section', array(
            'label'   => $this->__('Export to Server'),
            'title'   => $this->__('Export to Server'),
            'content' => $this->getLayout()->createBlock('gomage_feed/adminhtml_items_mappingexportftp_tab_file')->toHtml(),
        )
        );

        return parent::_beforeToHtml();

    }

}