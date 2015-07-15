<?php
 /**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2015 GoMage.com (http://www.gomage.com)
 * @author       GoMage.com
 * @license      http://www.gomage.com/licensing  Single domain license
 * @terms of use http://www.gomage.com/terms-of-use
 * @version      Release: 3.5
 * @since        Class available since Release 1.0
 */

class GoMage_Feed_Block_Adminhtml_Items_Mappingimport_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct(){
    	
        parent::__construct();
        $this->setId('gomage_feed_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle($this->__('Import'));
        
    }
    
    protected function _prepareLayout(){
        
        $this->addTab('filter_section', array(
	            'label'     =>  $this->__('Filter Configuration'),
	            'title'     =>  $this->__('Filter Configuration'),
	            'content'   =>  $this->getLayout()->createBlock('adminhtml/template')
	            				->setTemplate('gomage/feed/item/import/form.phtml')->toHtml(),
	        ));
    	
        return parent::_beforeToHtml();
        
    }
    
}