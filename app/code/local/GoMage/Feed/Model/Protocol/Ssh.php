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
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Protocol_Ssh extends GoMage_Feed_Model_Protocol_AbstractProtocol
{

    /**
     * @throws Mage_Core_Exception
     */
    protected function _connect()
    {
        $helper = Mage::helper('gomage_feed');
        if (!extension_loaded('ssh2')) {
            Mage::throwException($helper->__('SSH2 extension is not loaded.'));
        }

        $this->_connection = ssh2_connect($this->_params->getHost(), $this->_params->getPort());
        if (!$this->_connection) {
            Mage::throwException($helper->__('Invalid SFTP/SSH access (Host Name or Port).'));
        }
        if (!ssh2_auth_password($this->_connection, $this->_params->getUser(), $this->_params->getPassword())) {
            Mage::throwException($helper->__('Invalid SFTP/SSH access (User Name or Password).'));
        }
    }

    /**
     * @param  string $source
     * @param  string $dest
     * @return bool
     */
    public function execute($source, $dest)
    {
        $remoteFile = rtrim($dest, '/') . '/' . basename($source);
        return ssh2_scp_send($this->_connection, $source, $remoteFile);
    }
}