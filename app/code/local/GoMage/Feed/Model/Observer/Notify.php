<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2016 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/license-agreement/  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.0.0
 * @since        Class available since Release 3.2
 */
class GoMage_Feed_Model_Observer_Notify
{

    public function notify()
    {
        if (Mage::getSingleton('admin/session')->isLoggedIn() && Mage::getStoreConfig('gomage_notification/notification/enable')) {
            Mage::helper('gomage_feed')->notify();
        }
    }

}