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

	class GoMage_Feed_Adminhtml_ItemsController extends Mage_Adminhtml_Controller_Action{
	/*
	protected function _redirect($path, $arguments=array()){
    	
        $this->getResponse()->setRedirect(Mage::getModel('core/store')->load('0')->getUrl($path, $arguments));
        
        return $this;
    }
    
    /**/
	
	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('catalog/gomage_feed')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Feed Manager'), Mage::helper('adminhtml')->__('Feed Manager'));
		
		return $this;
	}   
 	
 	public function writeTempData(){
 		
 		if($feed_id = $this->getRequest()->getParam('id')){
 			
 			$feed = Mage::getModel('gomage_feed/item')->load($feed_id);
 			$start = intval($this->getRequest()->getParam('start'));
 			$length = intval($this->getRequest()->getParam('length'));
 			$feed->writeTempFile($start, $length);
 		}
 		
 	}
 	
	public function indexAction() {
		$this->_initAction();
		$this->renderLayout();
		
	}
	
	public function mappingimportAction() {
		
		if(($post = $this->getRequest()->getPost()) && ($id = $this->getRequest()->getParam('id')) && isset($_FILES['mappingfile']) && $_FILES['mappingfile']){
			
			try{
				
				$data = file_get_contents($_FILES['mappingfile']['tmp_name']);
				
				$array_data = json_decode($data);
				
				if(empty($array_data)){
					
					Mage::getSingleton('adminhtml/session')->addError(Mage::helper('core')->__('Empty or Invalid data file'));
					
				}else{
					
					Mage::getModel('gomage_feed/item')->load($id)->setContent($data)->save();
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('core')->__('Data successfully imported'));
					
				}
				
			}catch(Exception $e){
				
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('core')->__('Unknown error'));
				
			}
			
			return $this->_redirect('*/*/edit', array('id'=>$id, 'tab'=>'content_section'));
		}
		
		$this->_initAction();
		if($id = $this->getRequest()->getParam('id')){
        	Mage::register('gomage_feed', Mage::getModel('gomage_feed/item')->load($id));
			$this->_addContent($this->getLayout()->createBlock('gomage_feed/adminhtml_items_mappingimport'))
				->_addLeft($this->getLayout()->createBlock('gomage_feed/adminhtml_items_mappingimport_tabs'));
		
		
		}
		$this->renderLayout();
	}
	
	public function mappingexportAction(){
		
		
		if($feed_id = $this->getRequest()->getParam('id')){
			
			$feed = Mage::getModel('gomage_feed/item')->load($feed_id);
			
			$this->getResponse()->setBody($feed->getContent());
			$this->getResponse()->setHeader('Content-Type','text');
			$this->getResponse()->setHeader('Content-Disposition','attachment; filename="mapping-export-'.basename($feed->getFilename()).'.txt";');
			
		}
		
	}
	
	public function saveAction(){
		if ($data = $this->getRequest()->getPost()) {
			
			try{
			    $id = $this->getRequest()->getParam('id');
			    
				$model = Mage::getModel('gomage_feed/item');					  			
				
				
				if(isset($data['field'])){
					$content_data		 = array();
					$content_data_sorted = array();
					
					foreach($data['field'] as $field){
						if(intval($field['order']) && !isset($content_data_sorted[$field['order']])){
							
							$content_data_sorted[intval($field['order'])] = $field;
							
						}else{
							
							$field['order'] = 0;
							$content_data[] = $field;
						}
						
					}
					
					ksort($content_data_sorted);
					
					$data['content'] = json_encode(array_merge($content_data, $content_data_sorted));
					
				}
				
				if(isset($data['filter']) && is_array($data['filter'])){
				
					$data['filter'] = json_encode(array_merge($data['filter'], array()));
				
				}else{
					$data['filter'] = json_encode(array());
				}
				
				if(isset($data['upload_day']) && is_array($data['upload_day'])){
				
					$data['upload_day'] = implode(',',$data['upload_day']);
				
				}
				
	            $model->setData($data)->setId($id)->save();
	            
	            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('core')->__('Data successfully saved'));
	            
	            if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				
            }catch(Mage_Core_Exception $e){
            	
            	Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            	
            	Mage::getSingleton('core/session')->setFeedData($data);
            	
            	if($model->getId()>0){
            		$this->_redirect('*/*/edit', array('id' => $model->getId()));
            	}else{
            		$this->_redirect('*/*/new', array('type' => $model->getType()));
            	}
            	return false;
            	
            }catch(Exception $e){
            	
            	Mage::getSingleton('adminhtml/session')->addError(Mage::helper('core')->__('Can’t save data'));
            	
				Mage::getSingleton('core/session')->setFeedData($data);
			
            	if($model->getId()>0){
            		$this->_redirect('*/*/edit', array('id' => $model->getId()));
            	}else{
            		$this->_redirect('*/*/new', array('type' => $model->getType()));
            	}
            	return false;
            	
            }
            $this->_redirect('*/*/');
        }
	}
	
	public function deleteAction(){
		
		if($id = intval($this->getRequest()->getParam('id'))){
			
			$this->_deleteItems(array($id));
			
		}
		$this->_redirect('*/*/');
	}
	
	public function massDeleteAction(){
		
		if($ids = $this->getRequest()->getParam('id')){
			if(is_array($ids) && !empty($ids)){
				$this->_deleteItems($ids);
			}
			
		}
		
		$this->_redirect('*/*/');
		
	}
	
	public function massGenerateAction(){
		
		if($ids = $this->getRequest()->getParam('id')){
			if(is_array($ids) && !empty($ids)){
				foreach($ids as $id){
					try{
						
				        $feed =  Mage::getModel('gomage_feed/item')->load($id);
				        Mage::app()->setCurrentStore($feed->getStoreId());
				        $feed->generate();
				        
				        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('core')->__('File "%s" is created', $feed->getFilename()));
			    	
			    	}catch(Exception $e){
			    		
		            	Mage::getSingleton('adminhtml/session')->addError(Mage::helper('core')->__('Can’t generate feed file "%s"', $feed->getFilename()));
		            	
		            }
	            }
			}
			
		}
		
		$this->_redirect('*/*/');
		
	}
	public function massUploadAction(){
		
		if($ids = $this->getRequest()->getParam('id')){
			if(is_array($ids) && !empty($ids)){
				foreach($ids as $id){
					$item = Mage::getModel('gomage_feed/item')->load($id);
					
					try{
						
						if($item->getFtpActive() > 0){
							
				        	if($item->ftpUpload()){
				        		
				        		Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('core')->__('%s - File "%s" was uploaded!', $item->getName(), $item->getFilename()));
				        		
				        	}
				        	
			        	}else{
			        		
			        		Mage::getSingleton('adminhtml/session')->addNotice(Mage::helper('core')->__('%s - FTP disabled', $item->getName()));
			        		
			        	}
			    	
			    	}catch(Mage_Core_Exception $e){
		            	
		            	Mage::getSingleton('adminhtml/session')->addError($item->getName() . ' - ' . $e->getMessage());
		            	
		            }catch(Exception $e){
		            	
		            	Mage::getSingleton('adminhtml/session')->addError(Mage::helper('core')->__('%s - Can’t upload. Please, check your FTP Settings or Hosting Settings', $item->getFilename()));
		            	
		            }
	            }
			}
			
		}
		
		$this->_redirect('*/*/');
		
	}
	protected function _deleteItems($ids){
		if(is_array($ids) && !empty($ids)){
			foreach($ids as $id){
				
				$item = Mage::getModel('gomage_feed/item')->load($id);
				$item->delete();
				
			}
		}
	}
	
	public function newAction(){
		$this->_initAction();
		
		if($data = Mage::getSingleton('core/session')->getFeedData()){
        	Mage::register('gomage_feed', Mage::getModel('gomage_feed/item')->addData($data));
        	Mage::getSingleton('core/session')->setFeedData(null);
        }
		
		$this->_addContent($this->getLayout()->createBlock('gomage_feed/adminhtml_items_edit'))
				->_addLeft($this->getLayout()->createBlock('gomage_feed/adminhtml_items_edit_tabs'));
	
		$this->renderLayout();
		
	}
	
	public function editAction(){
		
		$this->_initAction();
		
		if($id = $this->getRequest()->getParam('id')){
        	Mage::register('gomage_feed', Mage::getModel('gomage_feed/item')->load($id));
        }
        
		$this->_addContent($this->getLayout()->createBlock('gomage_feed/adminhtml_items_edit'))
				->_addLeft($this->getLayout()->createBlock('gomage_feed/adminhtml_items_edit_tabs'));
		
		$this->renderLayout();
		
	}
	
	public function uploadAction(){
		
		if($id = $this->getRequest()->getParam('id')){
        	
        	$item = Mage::getModel('gomage_feed/item')->load($id);
        	
        	try{
        		
	        	if($item->ftpUpload()){
	        		
	        		Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('core')->__('File "%s" was uploaded!', $item->getFilename()));
	        		
	        	}
	    	
	    	}catch(Mage_Core_Exception $e){
            	
            	Mage::getSingleton('adminhtml/session')->addError($item->getFilename() . ' - ' . $e->getMessage());
            	
            }catch(Exception $e){
            	
            	Mage::getSingleton('adminhtml/session')->addError(Mage::helper('core')->__('%s - Can’t upload. Please, check your FTP Settings or Hosting Settings', $item->getFilename()));
            	
            }
            
            return $this->_redirect('*/*/edit', array('id'=>$id));
            
    	}
    	
    	$this->_redirect('*/*/index');
    	
    }
    public function generateAction(){
		
		if($id = $this->getRequest()->getParam('id')){
			
        	try{
        		
		        $feed =  Mage::getModel('gomage_feed/item')->load($id);
		        
		        Mage::app()->setCurrentStore($feed->getStoreId());
		        
		        $feed->generate();
		        
		        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('core')->__('File was generated!'));
	    	
	    	}catch(Mage_Core_Exception $e){
            	
            	Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            	
            }catch(Exception $e){
            	
            	Mage::getSingleton('adminhtml/session')->addError(Mage::helper('core')->__('Can’t generate feed file'));
            	
            }
            
    		return $this->_redirect('*/*/edit', array('id'=>$id));
    	
    	}
    	
    	return $this->_redirect('*/*/index');
    	
    }
    public function getattributevaluefieldAction(){
    	
    	
    	if($code = $this->getRequest()->getParam('attribute_code')){
    		
    		$name = $this->getRequest()->getParam('element_name');
    		
    		$attribute = Mage::getModel('catalog/product')->getResource()->getAttribute($code);
    		
    		if( $attribute && ($attribute->getFrontendInput() == 'select' || $attribute->getFrontendInput() == 'multiselect') ){
	        	
	        	$options = array();
			
				foreach($attribute->getSource()->getAllOptions() as $option){
					
					extract($option);
					
					$options[] = "<option value=\"{$value}\">{$label}</option>";
					
				}
				
				$this->getResponse()->setBody('<select style="width: 100%; border: 0pt none; padding: 0pt;" name="'.$name.'">'.implode('', $options).'</select>');
			
	        	
	        }else{
	        	
	        	$this->getResponse()->setBody('<input style="width:100%;border:0;padding:0;" type="text" class="input-text" name="'.$name.'" value=""/>');
	        	
	        }
    		
    	}
    	
    }

}