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
	
	class GoMage_Feed_Model_Item_Block_Product extends GoMage_Feed_Model_Item_Block{
		
		public function writeTempFile($curr_page = 0, $length= 50, $filename = ''){
			
			
			$products = $this->getFeed()->getProductsCollection('', $curr_page, $length);
			
			$stock_collection = Mage::getResourceModel('cataloginventory/stock_item_collection');
			
			$content = file_get_contents($this->getFeed()->getDirPath().'/feed-'.$this->getFeed()->getId().'-xml-product-block-template.tmp');
			
			//$content = Mage::getSingleton('core/session')->getXmlFeedProductTemplate();
			//$contents = array();
			
			$fp = fopen($this->getFeed()->getTempFilePath(), 'a');
			
			$log_fp = fopen(sprintf('%s/productsfeed/%s', Mage::getBaseDir('media'), 'log-'.$this->getFeed()->getId().'.txt'), 'a');
			
			$log_content = date("F j, Y, g:i:s a").', page:'.$curr_page.', items selected:'.count($products)."\n";
			
			$log_content .= $products->getSelect()."\n";
			
			fwrite($log_fp, $log_content);
			fclose($log_fp);
			
			$store = Mage::getModel('core/store')->load($this->getFeed()->getStoreId());
			
			foreach($products as $_product){
				
				$category_path = array();
				
				$category = null;
				
				
				
				
				
				foreach($_product->getCategoryIds() as $id){
					
					$_category = $this->getFeed()->getCategoriesCollection()->getItemById($id);
					
					if(is_null($category) || $category->getLevel() < $_category->getLevel()){
						
						$category = $_category;
						
					}
					
				}
				
				if($category){
				
					$category_path = array($category->getName());
					
					$parent_id = $category->getParentId();
					
					if($category->getLevel() > 2){
						
						while($_category = $this->getFeed()->getCategoriesCollection()->getItemById($parent_id)){
							
							if($_category->getLevel() < 2){
								break;
							}
							
							$category_path[] = $_category->getName();
							$parent_id = $_category->getParentId();
							
							
						}
						
					}
				
				}
				
				
				
				$product = new Varien_Object();
				
				if($category){
				
					$product->setCategory($category->getName());
					$product->setCategoryId($category->getEntityId());
					$product->setCategoryPath(implode(' -&gt; ', array_reverse($category_path)));
				
				}else{
					
					$product->setCategory('');
					$product->setCategoryPath('');
					
				}
				$template_attributes = $this->getAllVars($content);
				
				//foreach($_product->getAttributes() as $attribute_code=>$attribute_model){
				
				$custom_attributes = Mage::getResourceModel('gomage_feed/custom_attribute_collection');
    			$custom_attributes->load();
				
				foreach($template_attributes as $attribute_code){
					
					$value = null;
					
					
					switch($attribute_code):
					
					case ('store_price'):
						
						$value = $store->convertPrice($_product->getFinalPrice(), false, false);
						
					break;
					
					case ('parent_url'):
						
						if(($parent_product = $this->getFeed()->getParentProduct($_product, $products)) && $parent_product->getEntityId() > 0){
							
							$product->setParentUrl($parent_product->getProductUrl(false));
							
							
							break;
						}
						
						$product->setParentUrl($_product->getProductUrl(false));
						
					break;
					
					case('url'):
						$product->setUrl($_product->getProductUrl(false));
					break;
					
					default:
					
					if(strpos($attribute_code, 'custom:') === 0){
						
						$custom_code = trim(str_replace('custom:', '', $attribute_code));
						
						if($custom_attribute = $custom_attributes->getItemByColumnValue('code', $custom_code)){
							
							$options = Zend_Json::decode($custom_attribute->getData('data'));
							
							foreach($options as $option){
								
								foreach($option['condition'] as $condition){
									
									$attr_value = $_product->getData($condition['attribute_code']);
									$cond_value = $condition['value'];
									
									$is_multi = false;
									
									if($product_attribute = $_product->getResource()->getAttribute($condition['attribute_code'])){
										
										if($product_attribute->getFrontendInput() == 'multiselect'){
											
											$is_multi = true;
											$attr_value = explode(',', $attr_value);
										
										}
										
									}
									
									switch($condition['condition']):
										
										case('eq'):
											
											if(!$is_multi && $attr_value == $cond_value || $is_multi && in_array($cond_value, $attr_value)){
												
												continue 2;
												
											}else{
												
												continue 3;
												
											}
										
										break;
										
										case('neq'):
										
											if(!$is_multi && $attr_value != $cond_value || $is_multi && !in_array($cond_value, $attr_value)){
												
												continue 2;
												
											}else{
												
												continue 3;
												
											}
										
										break;
										
										case('gt'):
										
											if($attr_value > $cond_value){
												
												continue 2;
												
											}else{
												
												continue 3;
												
											}
										
										break;
										
										case('lt'):
										
											if($attr_value < $cond_value){
												
												continue 2;
												
											}else{
												
												continue 3;
												
											}
										
										break;
										
										case('gteq'):
										
											if($attr_value >= $cond_value){
												
												continue 2;
												
											}else{
												
												continue 3;
												
											}
										
										break;
										
										case('lteq'):
										
											if($attr_value <= $cond_value){
												
												continue 2;
												
											}else{
												
												continue 3;
												
											}
										
										break;
										
										case('like'):
										
											if(strpos($attr_value, $cond_value) !== false){
												
												continue 2;
												
											}else{
												
												continue 3;
												
											}
										
										break;
										
										case('nlike'):
										
											if(strpos($attr_value, $cond_value) === false){
												
												continue 2;
												
											}else{
												
												continue 3;
												
											}
										
										break;
										
									endswitch;
									
								
								}
								
								
								
								if($option['value_type'] == 'percent'){
									
									$value = floatval($_product->getData($option['value_type_attribute']))/100*floatval($option['value']);
									
								}else{
									
									$value = $option['value'];
									
								}
								
								break;
								
							}
							
							if($value === null && $custom_attribute->getDefaultValue()){
								
								$value = $_product->getData($custom_attribute->getDefaultValue());
								
							}
							
						}
						
						
						
					}elseif($attribute_model = $_product->getResource()->getAttribute($attribute_code)){

						switch($attribute_model->getFrontendInput()):
							
							case ('select'): case ('multiselect'):
								
								$value = implode(', ', (array)$_product->getAttributeText($attribute_code));
								
							break;
							
							default:
								
								$value = $_product->getData($attribute_code);
								
							break;
							
						endswitch;
					
					}
					
					if($value && !$product->getData($attribute_code)){
						$product->setData($attribute_code, $value);
					}
					
					break;
					
					endswitch;
						
				}
				
				$product->setDescription(strip_tags(preg_replace('/<br.*?>/s', "\r\n", $_product->getDescription())));
				$product->setShortDescription(strip_tags(preg_replace('/<br.*?>/s', "\r\n", $_product->getShortDescription())));
				
				if($stock_item = $stock_collection->getItemByColumnValue('product_id', $_product->getId())){
				
				$product->setQty(ceil($stock_item->getQty()));
				
				}else{
				
				$product->setQty(0);
				
				}
				
				try{
					
					$product->setImage((string)Mage::helper('catalog/image')->init($_product, 'image'));
					
				}catch(Exception $e){
					
					$product->setImage('');
					
				}
				
				$product->setQty(ceil(Mage::getModel('cataloginventory/stock_item')->loadByProduct($_product)->getQty()));
				
				//$contents[] = parent::setVars($content, $product);
				
				fwrite($fp, parent::setVars($content, $product)."\r\n");
				
			}
			
			
			fclose($fp);
			
		}
		
		public function setVars($content = null, $dataObject=null, $remove_empty=false){
			
			$template_temp_file = $this->getFeed()->getDirPath().'/feed-'.$this->getFeed()->getId().'-xml-product-block-template.tmp';
			
			file_put_contents($template_temp_file, $content);
			//Mage::getSingleton('core/session')->setXmlFeedProductTemplate($content);
			
			$collection = $this->getFeed()->getProductsCollection();
			$total_products = $collection->getSize();
			
			$per_page = intval($this->getFeed()->getIterationLimit());
			
			if($per_page){
			
				$pages = ceil($total_products/$per_page);
			
			}else{
				
				$pages = 1;
				$per_page = 0;
			}
		
			file_put_contents(sprintf('%s/productsfeed', Mage::getBaseDir('media')).'/log-'.$this->getFeed()->getId().'.txt', "started at:".date("F j, Y, g:i:s a").", pages:{$pages}, per_page:{$per_page} \n");

			for($i = 0;$i<$pages;$i++){

				if ($_fp = fopen(Mage::getModel('core/store')->load($this->getFeed()->getStoreId())->getUrl('feed/index/index', array('id'=>$this->getFeed()->getId(), 'start'=>$i, 'length'=>$per_page, '_nosid' => true)), 'r')){

					$contents = '';
					while (!feof($_fp)) {
					  $contents .= fread($_fp, 8192);
					}

		            $response = Zend_Json::decode($contents);
		            
		            if(!isset($response['result']) || !$response['result']){
		            	
		            	throw new Mage_Core_Exception(Mage::helper('gomage_feed')->__('There was an error while generating file. Please try to change Number of Products in the Advanced Settings tab (not 0) or check server settings..'));
		            	
		            }
		            
		        }else{
		        	
		        	throw new Mage_Core_Exception(Mage::helper('gomage_feed')->__('Cant open temp file'));
		        	
		        }
		        
		        
		        fclose($_fp);

	        }
	        
			$content = file_get_contents($this->getFeed()->getTempFilePath());
			
			unlink($template_temp_file);
			unlink($this->getFeed()->getTempFilePath());

			return $content;

			/*

			$products = $this->getFeed()->getProductsCollection();
			$stock_collection = Mage::getResourceModel('cataloginventory/stock_item_collection');
			$contents = array();

			foreach($products as $_product){

				$category_path = array();

				$category = null;

				foreach($_product->getCategoryIds() as $id){

					$_category = $this->getFeed()->getCategoriesCollection()->getItemById($id);

					if(is_null($category) || $category->getLevel() < $_category->getLevel()){
						
						$category = $_category;
						
					}
					
				}
				
				if($category){
				
					$category_path = array($category->getName());
					
					$parent_id = $category->getParentId();
					
					if($category->getLevel() > 2){
						
						while($_category = $this->getFeed()->getCategoriesCollection()->getItemById($parent_id)){
							
							if($_category->getLevel() < 2){
								break;
							}
							
							$category_path[] = $_category->getName();
							$parent_id = $_category->getParentId();
							
							
						}
						
					}
				
				}
				
				$product = new Varien_Object();
				
				if($category){
				
					$product->setCategory($category->getName());
					$product->setCategoryPath(implode(' -&gt; ', array_reverse($category_path)));
				
				}else{
					
					$product->setCategory('');
					$product->setCategoryPath('');
					
				}
				
				foreach($_product->getAttributes() as $attribute_code=>$attribute_model){
					
					switch($attribute_model->getFrontendInput()):
						
						case ('select'): case ('multiselect'):
							
							$product->setData($attribute_code, $_product->getAttributeText($attribute_code));
							
						break;
						
						default:
							
							$product->setData($attribute_code, $_product->getData($attribute_code));
							
						break;
						
					endswitch;
					
				}
				
				$product->setUrl($_product->getProductUrl(false));
				
				
				$product->setDescription(strip_tags(preg_replace('/<br.*?>/s', "\r\n", $_product->getDescription())));
				$product->setShortDescription(strip_tags(preg_replace('/<br.*?>/s', "\r\n", $_product->getShortDescription())));
				$product->setQty(ceil($stock_collection->getItemByColumnValue('product_id', $_product->getId())->getQty()));
				
				try{
					
					$product->setImage((string)Mage::helper('catalog/image')->init($_product, 'image'));
					
				}catch(Exception $e){
					
					$product->setImage('');
					
				}
				
				$product->setQty(ceil(Mage::getModel('cataloginventory/stock_item')->loadByProduct($_product)->getQty()));
				
				$contents[] = parent::setVars($content, $product);
				
			}
			
			return implode("\r\n", $contents);
			/**/
		}
		
	}