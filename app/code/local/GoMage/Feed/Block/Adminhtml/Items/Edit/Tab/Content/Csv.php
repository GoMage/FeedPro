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

class GoMage_Feed_Block_Adminhtml_Items_Edit_Tab_Content_Csv extends Mage_Adminhtml_Block_Template
{
	
	protected $attribute_collection;
	protected $options;
	
	
	protected function _prepareLayout()
    {
        $this->setChild('uploader',
            $this->getLayout()->createBlock('adminhtml/media_uploader')
        );
	}
	
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
			
			$options['Product Id'] = array('code'=>"entity_id", 'label' => "Product Id");
	    	$options['Is In Stock'] = array('code'=>"is_in_stock" , 'label' =>  "Is In Stock");
	    	$options['Qty'] = array('code'=>"qty" , 'label' =>  "Qty");
	    	$options['Image'] = array('code'=>"image" , 'label' =>  "Image");
	    	$options['URL'] = array('code'=>"url" , 'label' =>  "URL");
	    	$options['URL (Parent product)'] = array('code'=>"parent_url" , 'label' =>  "URL (Parent product)");
	    	$options['Category'] = array('code'=>"category", 'label' =>  "Category");
	    	$options['Final Price'] = array('code'=>"final_price", 'label' =>  "Final Price");
	    	$options['Store Price'] = array('code'=>"store_price", 'label' =>  "Store Price");
			
			
			$custom_attributes = Mage::getResourceModel('gomage_feed/custom_attribute_collection');
			
			foreach($custom_attributes as $attribute){
				
				$label = '* '.$attribute->getName();
				
				$options[$label] = array('code'=>sprintf('custom:%s', $attribute->getCode()), 'label'=>$label);
				
			}
					
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
	public function getAttributeSelect($i, $current = null, $active = true){
		
		$options = array();
		
		$options[] = "<option value=''>".$this->__('Not Set')."</option>";
		
		foreach($this->getAttributeOptionsArray() as $attribute){
			
			extract($attribute);
			
			$selected = '';
			
			if($code == $current){
				$selected = 'selected="selected"';
			}
			
			$options[] = "<option value=\"{$code}\" {$selected}>{$label}</option>";
			
		}
		
		return '<select style="width:260px;display:'.($active ? 'block' : 'none').'" id="mapping-'.$i.'-attribute-value" name="field['.$i.'][attribute_value]">'.implode('', $options).'</select>';
		
	}
    
  
}