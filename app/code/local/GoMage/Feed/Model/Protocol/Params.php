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

/**
 * Class Params
 *
 * @method string getHost)
 * @method int getPort()
 * @method string getUser()
 * @method string getPassword()
 * @method bool getPassiveMode()
 */
class GoMage_Feed_Model_Protocol_Params extends Varien_Object
{

    const PORT = 21;

    public function __construct(array $data = [])
    {
        $data = $this->validate($data);
        parent::__construct($data);
    }

    /**
     * @param  array $data
     * @return array
     * @throws Mage_Core_Exception
     */
    protected function validate(array $data)
    {
        if (!isset($data['host']) || !$data['host']) {
            Mage::throwException(Mage::helper('gomage_feed')->__('Host is not specified.'));
        }
        if (!isset($data['user']) || !$data['user']) {
            Mage::throwException(Mage::helper('gomage_feed')->__('User is not specified.'));
        }
        if (!isset($data['port']) || !$data['port']) {
            $data['port'] = self::PORT;
        }
        $data['passive_mode'] = isset($data['passive_mode']) && boolval($data['passive_mode']);
        return $data;
    }
}
