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
abstract class GoMage_Feed_Model_Protocol_AbstractProtocol implements GoMage_Feed_Model_Protocol_ProtocolInterface
{
    /**
     * @var GoMage_Feed_Model_Protocol_Params
     */
    protected $_params;

    /**
     * @var resource
     */
    protected $_connection;


    public function __construct(GoMage_Feed_Model_Protocol_Params $params)
    {
        $this->_params = $params;
        $this->_connect();
    }

    /**
     * @return resource
     */
    abstract protected function _connect();
}