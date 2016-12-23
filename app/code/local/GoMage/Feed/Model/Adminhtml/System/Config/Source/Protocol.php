<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2016 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.0.0
 * @since        Class available since Release 3.3
 */
class GoMage_Feed_Model_Adminhtml_System_Config_Source_Protocol
{
    public function toOptionArray()
    {
        $helper = Mage::helper('gomage_feed');
        return array(
            array('value' => GoMage_Feed_Model_Protocol_ProtocolInterface::FTP, 'label' => $helper->__('FTP / FTPS')),
            array('value' => GoMage_Feed_Model_Protocol_ProtocolInterface::SSH, 'label' => $helper->__('SFTP (SSH)')),
        );
    }

    public static function toOptionHash()
    {
        $helper = Mage::helper('gomage_feed');

        return array(
            array('value' => GoMage_Feed_Model_Protocol_ProtocolInterface::FTP, 'label' => $helper->__('FTP / FTPS')),
            array('value' => GoMage_Feed_Model_Protocol_ProtocolInterface::SSH, 'label' => $helper->__('SFTP (SSH)')),
        );
    }

}