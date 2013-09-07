<?php
 /**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010 GoMage.com (http://www.gomage.com)
 * @author       GoMage.com
 * @license      http://www.gomage.com/licensing  Single domain license
 * @terms of use http://www.gomage.com/terms-of-use
 * @version      Release: 1.1
 * @since        Class available since Release 1.0
 */

class GoMage_Feed_Block_Adminhtml_Items_Edit_Tab_Main extends Mage_Adminhtml_Block_Widget_Form
{
	
	
    protected function _prepareForm()
    {
        
        $form = new Varien_Data_Form();
        
        if(Mage::registry('gomage_feed')){
        	$item = Mage::registry('gomage_feed');
        }else{
        	$item = new Varien_Object();
        }
        
        $this->setForm($form);
        $fieldset = $form->addFieldset('main_fieldset', array('legend' => $this->__('Item information')));
     	
     	$fieldset->addField('type', 'hidden', array(
            'name'      => 'type',
        ));
     	
    	$fieldset->addField('name', 'text', array(
            'name'      => 'name',
            'label'     => $this->__('Name'),
            'title'     => $this->__('Name'),
            'required'  => true,
            'note'	=>$this->__('e.g. "Google Base", "Yahoo! Store"...')
        ));
        if($item->getId() && ($url = $item->getUrl())){
        	
		        $fieldset->addField('comments', 'note', array(
		            'label'    => $this->__('Access Url'),
		            'title'    => $this->__('Access Url'),
		            'text'    => '<a href="'.$url.'" target="_blank">'.$url.'</a>',
		        ));
	        
        	
        }
        
        $fieldset->addField('filename', 'text', array(
            'name'      => 'filename',
            'label'     => $this->__('Filename'),
            'title'     => $this->__('Filename'),
            'required'  => false,
            'note'	=> $this->__('e.g. "productfeed.csv", "productfeed.xml"...'),
        ));
        
        $fieldset->addField('store_id', 'select', array(
            'label'     => $this->__('Store'),
            'required'  => true,
            'name'      => 'store_id',
            'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(),
        ));
       /*
        $fieldset->addField('content', 'textarea', array(
            'name'      => 'content',
            'label'     => $this->__('Content'),
            'title'     => $this->__('Content'),
            'required'  => true,
            'style'     => 'width:500px;height:250px;'
        ));
        
        $fieldset->addField('filter', 'textarea', array(
            'name'      => 'filter',
            'label'     => $this->__('Filter Config'),
            'title'     => $this->__('Filter Config'),
            'required'  => false,
            'style'     => 'width:500px;height:150px;'
        ));
        */
        if(!$item->getType() && $this->getRequest()->getParam('type')){
        	$item->setType($this->getRequest()->getParam('type'));
        }
        
        $form->setValues($item->getData());
        
        
        return parent::_prepareForm();
        
    }
    
  
}