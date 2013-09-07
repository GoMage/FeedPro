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
	
	class GoMage_Feed_Model_Observer{
		
		static function proccessFeeds(){
			$collection = Mage::getResourceModel('gomage_feed/item_collection');
			
			$current_time = date('G');
			
			$collection->getSelect()->where('`upload_day` like "%'.strtolower(date('D')).'%"');
			$collection->getSelect()->where('`upload_hour` <= ?', $current_time);
			
			foreach($collection as $feed){
				
				if($current_time == date('G', strtotime($feed->getData('cron_started_at')))){
					
					continue;
					
				}
				
				$hours_at_last_generated = ((time()-strtotime($feed->getData('cron_started_at'))) / (60*60));
				$hours_passed = date('G')-$feed->getData('upload_hour');
				
				if($feed->getData('upload_hour') == $current_time || ($hours_passed >= 1 && $hours_passed % $feed->getUploadInterval() == 0) || $hours_at_last_generated >= $feed->getUploadInterval()){
				
					try{
						Mage::app()->setCurrentStore($feed->getStoreId());
						
						$feed->setData('cron_started_at', date('Y-m-j H:i:s', time()));
						
						$feed->generate();
						$feed->ftpUpload();
						
						$feed->save();
						
					}catch(Exception $e){
						
						continue;
						
					}
				
				}
			}
			
		}
		
		static function generateAll(){
			
			
			$collection = Mage::getResourceModel('gomage_feed/item_collection');
			
			foreach($collection as $feed){
				try{
					Mage::app()->setCurrentStore($feed->getStoreId());
					$feed->generate();
				}catch(Exception $e){
					continue;
				}
			}
			
		}
		
		static function uploadAll(){
			
			$collection = Mage::getResourceModel('gomage_feed/item_collection');
			
			foreach($collection as $feed){
				try{
					Mage::app()->setCurrentStore($feed->getStoreId());
					$feed->ftpUpload();
				}catch(Exception $e){
					continue;
				}
			}
			
		}
		
	}