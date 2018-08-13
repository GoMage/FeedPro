<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2017 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/license-agreement/  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.2.0
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Protocol_Ftp extends GoMage_Feed_Model_Protocol_AbstractProtocol
{

    /**
     * @throws Mage_Core_Exception
     */
    protected function _connect()
    {
        $helper = Mage::helper('gomage_feed');

        if (!extension_loaded('ftp')) {
            Mage::throwException($helper->__('FTP extension is not loaded.'));
        }

        $this->_connection = ftp_connect($this->_params->getHost(), $this->_params->getPort());
        if (!$this->_connection) {
            Mage::throwException($helper->__('Invalid FTP/FTPS access (Host Name or Port).'));
        }
        if (!ftp_login($this->_connection, $this->_params->getUser(), $this->_params->getPassword())) {
            Mage::throwException($helper->__('Invalid FTP/FTPS access (User Name or Password).'));
        }
        if (!ftp_pasv($this->_connection, $this->_params->getPassiveMode())) {
            Mage::throwException($helper->__('Invalid FTP/FTPS access (Passive/Active Mode).'));
        }
    }

    /**
     * @param  string $source
     * @param  string $dest
     * @return bool
     */
    public function execute($source, $dest)
    {
        ftp_chdir($this->_connection, $dest);
        return ftp_put($this->_connection, basename($source), $source, FTP_BINARY);
    }
}