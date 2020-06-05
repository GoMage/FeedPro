<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2020 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/license-agreement/  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.3.2
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Uploader
{

    /**
     * @var Mage_Core_Model_Date
     */
    protected $_dateTime;

    public function __construct()
    {
        $this->_dateTime = Mage::getModel('core/date');
    }

    public function upload($feedId)
    {
        /** @var GoMage_Feed_Model_Item $feed */
        $feed = Mage::getModel('gomage_feed/item')->load($feedId);
        if ($this->_validate($feed)) {

            /** @var GoMage_Feed_Model_Protocol_Factory $protocolFactory */
            $protocolFactory = Mage::getModel('gomage_feed/protocol_factory');

            $protocol = $protocolFactory->create($feed->getData('ftp_protocol'),
                [
                    'host'         => $feed->getData('ftp_host'),
                    'port'         => $feed->getData('ftp_port'),
                    'user'         => $feed->getData('ftp_user_name'),
                    'password'     => $feed->getData('ftp_user_pass'),
                    'passive_mode' => $feed->getData('ftp_passive_mode'),
                ]
            );

            $source = Mage::helper('gomage_feed/generator')->getBaseDir() . DS . $feed->getFileNameWithExt();

            if ($protocol->execute($source, $feed->getFtpDir())) {
                $feed->setData('uploaded_at', $this->_dateTime->gmtDate('Y-m-j H:i:s'))->save();
            }
        }
    }

    protected function _validate(GoMage_Feed_Model_Item $feed)
    {
        $helper = Mage::helper('gomage_feed');

        if (!$feed->getFtpActive()) {
            Mage::throwException($helper->__('FTP Uploading is disabled for this feed.'));
        }

        if (!file_exists(Mage::helper('gomage_feed/generator')->getBaseDir() . DS . $feed->getFileNameWithExt())) {
            Mage::throwException($helper->__('File not found. Please generate a feed.'));
        }

        return true;
    }


}