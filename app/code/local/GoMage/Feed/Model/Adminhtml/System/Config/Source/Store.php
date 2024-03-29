<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2020 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.3.2
 * @since        Class available since Release 1.0
 */
class GoMage_Feed_Model_Adminhtml_System_Config_Source_Store extends Mage_Adminhtml_Model_System_Store
{
    protected function _loadWebsiteCollection()
    {
        $h                  = Mage::helper('gomage_feed');
        $_websiteCollection = array();
        foreach ($h->getAvailavelWebsites() as $_w) {
            $website = Mage::getModel('core/website')->load($_w);
            if ($website->getId() == 0) {
                continue;
            }
            $_websiteCollection[$website->getId()] = $website;
        }
        $this->_websiteCollection = $_websiteCollection;
        return $this;
    }
}