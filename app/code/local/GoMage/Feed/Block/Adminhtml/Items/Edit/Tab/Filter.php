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

class GoMage_Feed_Block_Adminhtml_Items_Edit_Tab_Filter extends Mage_Adminhtml_Block_Template
{
	
	protected $attribute_collection;
	protected $options;
	
	public function getFeed(){
		
		if(Mage::registry('gomage_feed')){
        	return Mage::registry('gomage_feed');
        }else{
        	return  new Varien_Object();
        }
		
	}
	
	public function getAttributeCollection(){
		if(is_null($this->attribute_collection)){
			
			$this->attribute_collection = Mage::getResourceModel('eav/entity_attribute_collection')
				->setItemObjectClass('catalog/resource_eav_attribute')
				->setEntityTypeFilter(Mage::getResourceModel('catalog/product')->getTypeId());
			
		}
		return $this->attribute_collection;
	}
	
	public function getAttributeOptionsArray(){
		
		if(is_null($this->options)){
			
			$options = array();
			
			foreach($this->getAttributeCollection() as $attribute){
				if($attribute->getFrontendLabel()){
				$options[$attribute->getFrontendLabel()] = array('code'=>$attribute->getAttributeCode(), 'label'=>($attribute->getFrontendLabel() ? $attribute->getFrontendLabel() : $attribute->getAttributeCode()));
				}
				
			}
			
			ksort($options);
			
			$this->options = $options;
			
		}
		
		
		
		return $this->options;
		
	}
	public function getAttributeValueField($code = '', $name = '', $current = ''){
		
		if($code){
			
			$attribute = Mage::getModel('catalog/product')->getResource()->getAttribute($code);
    		
    		if($attribute->getFrontendInput() == 'select' || $attribute->getFrontendInput() == 'multiselect'){
	        	
	        	$options = array();
			
				foreach($attribute->getSource()->getAllOptions() as $option){
					
					extract($option);
					
					$selected = '';
					
					if($current == $value){
						$selected = 'selected="selected"';
					}
					
					$options[] = "<option value=\"{$value}\" {$selected}>{$label}</option>";
					
				}
				
				return ('<select style="width: 100%; border: 0pt none; padding: 0pt;" name="'.$name.'">'.implode('', $options).'</select>');
			
	        	
	        }else{
	        	
	        	return ('<input style="width:100%;border:0;padding:0;" type="text" class="input-text" name="'.$name.'" value="'.$current.'"/>');
	        	
	        }
			
		}
		
	}
	
	public function getAttributeSelect($i, $current = null, $active = true, $additional = ''){
		
		$options = array();
		
		foreach($this->getAttributeOptionsArray() as $attribute){
			
			extract($attribute);
			
			$selected = '';
			
			if($code == $current){
				$selected = 'selected="selected"';
			}
			
			$options[] = "<option value=\"{$code}\" {$selected}>{$label}</option>";
			
		}
		
		
		
		return '<select '.$additional.' style="width:260px;display:'.($active ? 'block' : 'none').'" id="filter-'.$i.'-attribute-code" name="filter['.$i.'][attribute_code]">'.implode('', $options).'</select>';
		
	}
	public function getConditionSelect($i, $current = null){
		
		$options = array(
			'<option '.($current == 'eq' ? 'selected="selected"' : '').' value="eq">'.$this->__('equal').'</option>',
			'<option '.($current == 'neq' ? 'selected="selected"' : '').' value="neq">'.$this->__('not equal').'</option>',
			'<option '.($current == 'gt' ? 'selected="selected"' : '').' value="gt">'.$this->__('greater than').'</option>',
			'<option '.($current == 'lt' ? 'selected="selected"' : '').' value="lt">'.$this->__('less than').'</option>',
			'<option '.($current == 'gteq' ? 'selected="selected"' : '').' value="gteq">'.$this->__('greater than or equal to').'</option>',
			'<option '.($current == 'lteq' ? 'selected="selected"' : '').' value="lteq">'.$this->__('less than or equal to').'</option>',
			'<option '.($current == 'like' ? 'selected="selected"' : '').' value="like">'.$this->__('like').'</option>',
			'<option '.($current == 'nlike' ? 'selected="selected"' : '').' value="nlike">'.$this->__('not like').'</option>',
		);
		
		return '<select style="width:160px" id="filter-'.$i.'-condition" name="filter['.$i.'][condition]">'.implode('', $options).'</select>';
	}
    
  
}