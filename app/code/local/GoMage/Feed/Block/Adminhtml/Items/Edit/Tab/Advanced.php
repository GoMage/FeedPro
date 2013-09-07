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

class GoMage_Feed_Block_Adminhtml_Items_Edit_Tab_Advanced extends Mage_Adminhtml_Block_Widget_Form
{
	
	
    protected function _prepareForm()
    {
        
        $form = new Varien_Data_Form();
        
        if(Mage::registry('gomage_feed')){
        	$item = Mage::registry('gomage_feed');
        }else{
        	$item = Mage::getModel('gomage_feed/item');
        }
        
        $this->setForm($form);
        $fieldset = $form->addFieldset('advanced', array('legend' => $this->__('File Creation Settings')));
        
        
        $field = $fieldset->addField('iteration_limit', 'text', array(
            'name'      => 'iteration_limit',
            'label'     => $this->__('Number of Products'),
            'title'     => $this->__('Number of Products'),
            'required'  => true,
            'note'		=> '"0" = All products. <br/>This option allows to optimize file creation for low memory servers.<br/>You have to increase php memory_limit before changing this value to maximum.',
            
        ));
        if(!$item->getId()){
        
        	$field ->setValue('0');
        
        }
        $field = $fieldset->addField('use_layer', 'select', array(
            'name'      => 'use_layer',
            'label'     => $this->__('Export Disabled and Out of stock Products'),
            'title'     => $this->__('Export Disabled and Out of stock Products'),
            'required'  => false,
            'values'	=> array(1=>$this->__('No'),0=>$this->__('Yes'))
        	));
        
        
        if(!$item->getId()){
        
        	$field ->setValue('1');
        
        }
        $fieldset = $form->addFieldset('upload_settings', array('legend' => $this->__('Upload Settings')));
        
        $field = $fieldset->addField('upload_day', 'multiselect', array(
            'name'      => 'upload_day',
            'label'     => $this->__('Available Upload Days'),
            'title'     => $this->__('Available Upload Days'),
            'required'  => false,
            'values'	=> array(
            	array('label'=>$this->__('Sunday'), 'value'=>'sun'),
            	array('label'=>$this->__('Monday'), 'value'=>'mon'),
            	array('label'=>$this->__('Tuesday'), 'value'=>'tue'),
            	array('label'=>$this->__('Wednesday'), 'value'=>'wed'),
            	array('label'=>$this->__('Thursday'), 'value'=>'thu'),
            	array('label'=>$this->__('Friday'), 'value'=>'fri'),
            	array('label'=>$this->__('Saturday'), 'value'=>'sat'),
            )
        ));
        
        if(!$item->getId()){
        
        	$field ->setValue('sun,mon,tue,wed,thu,fri,sat');
        
        }
        
        $hours = array();
        
        $locale = Mage::getSingleton('core/locale');
        
        
        for($i = 0;$i<24;$i++){
        	
        	$hours[] = array('label'=>sprintf('%02d:00',$i), 'value'=>date('H', mktime($i, 0, 0, 1, 1, 1970)+$locale->date()->getGmtOffset()));
        	
        }
        
        
        $fieldset->addField('upload_hour', 'select', array(
            'name'      => 'upload_hour',
            'label'     => $this->__('Set Upload Time from, hour'),
            'title'     => $this->__('Set Upload Time from, hour'),
            'required'  => false,
            'values'	=> $hours
        ));
        
        $field = $fieldset->addField('upload_interval', 'select', array(
            'name'      => 'upload_interval',
            'label'     => $this->__('Set Interval for Upload, hours'),
            'title'     => $this->__('Set Interval for Upload, hours'),
            'required'  => false,
            'values'	=> array(
            	
            	array('label'=>$this->__('every 1 hour'), 'value'=>1),
            	array('label'=>$this->__('every 3 hours'), 'value'=>3),
            	array('label'=>$this->__('every 6 hours'), 'value'=>6),
            	array('label'=>$this->__('every 12 hours'), 'value'=>12),
            	array('label'=>$this->__('every 24 hours'), 'value'=>24),
            	
            )
        ));
        if(!$item->getId()){
        
        	$field ->setValue('24');
        
        }
        
        
        if($item->getId()){
        
        	$form->setValues($item->getData());
        
        }
        
        
        return parent::_prepareForm();
        
    }
    
  
}