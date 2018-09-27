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
 * @since        Class available since Release 1.0
 */
class GoMage_Feed_Block_Adminhtml_Items extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller     = 'adminhtml_items';
        $this->_blockGroup     = 'gomage_feed';
        $this->_headerText     = $this->__('Manage Feeds');
        $this->_addButtonLabel = $this->__('Add Item');
        parent::__construct();
    }
}