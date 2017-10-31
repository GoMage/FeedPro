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
 * @version      Release: 4.0.0
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Protocol_Factory
{

    /**
     * @var array
     */
    protected $_protocols = array(
        GoMage_Feed_Model_Protocol_ProtocolInterface::FTP => 'gomage_feed/protocol_ftp',
        GoMage_Feed_Model_Protocol_ProtocolInterface::SSH => 'gomage_feed/protocol_ssh',
    );

    /**
     * @param  int $protocol
     * @param  array $data
     * @return GoMage_Feed_Model_Protocol_ProtocolInterface
     */
    public function create($protocol, $data = array())
    {
        /** @var GoMage_Feed_Model_Protocol_Params $params */
        $params = Mage::getModel('gomage_feed/protocol_params', $data);
        return Mage::getModel($this->_protocols[(int)$protocol], $params);
    }

}
