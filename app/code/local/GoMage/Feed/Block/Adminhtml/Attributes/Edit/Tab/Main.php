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

class GoMage_Feed_Block_Adminhtml_Attributes_Edit_Tab_Main extends Mage_Adminhtml_Block_Widget_Form
{
	
	
    protected function _prepareForm()
    {
        
        $form = new Varien_Data_Form();
        
        if(Mage::registry('gomage_custom_attribute')){
        	$item = Mage::registry('gomage_custom_attribute');
        }else{
        	$item = new Varien_Object();
        }
        
        $this->setForm($form);
        $fieldset = $form->addFieldset('main_fieldset', array('legend' => $this->__('Attribute Information')));
     	
     	$fieldset->addField('type', 'hidden', array(
            'name'      => 'type',
        ));
     	/*
     	$field = $fieldset->addField('attribute_code', 'text', array(
            'name'      => 'attribute_code',
            'label'     => $this->__('Catalog Attribute Code'),
            'title'     => $this->__('Catalog Attribute Code'),
            'required'  => false,
            'readonly'  => true,
            'style'		=> 'background:#eee;color:#666;',
        ));
        
     	if(!$item->getId()){
        
        	$field->setValue($this->getRequest()->getParam('code'));
        
        }
        /**/
        $fieldset->addField('code', 'text', array(
            'name'      => 'code',
            'label'     => $this->__('Dynamic Attribute Code'),
            'title'     => $this->__('Dynamic Attribute Code'),
            'required'  => true,
            'class'		=> 'validate-code',
            'note'		=> $this->__('For internal use. Must be unique with no spaces'),
        ));
        
    	$fieldset->addField('name', 'text', array(
            'name'      => 'name',
            'label'     => $this->__('Name'),
            'title'     => $this->__('Name'),
            'required'  => true,
            'note'		=>$this->__('e.g. "Custom Price", "Google Category"...')
        ));
        
        if(!$item->getType() && $this->getRequest()->getParam('type')){
        	$item->setType($this->getRequest()->getParam('type'));
        }
        
        if($item->getId()){
        
        	$form->setValues($item->getData());
        
        }
        
        
        return parent::_prepareForm();
        
    }
    
  
}