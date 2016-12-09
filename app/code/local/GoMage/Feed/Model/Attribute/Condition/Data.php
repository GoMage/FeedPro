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
 * @version      Release: 3.7.0
 * @since        Class available since Release 4.0.0
 */

/**
 * Class Data
 *
 * @method string getCode()
 * @method string getOperator()
 * @method string getValue()
 */
class GoMage_Feed_Model_Attribute_Condition_Data extends Varien_Object
{

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
        if (!isset($data['code']) || !$data['code']) {
            throw new Mage_Core_Exception(Mage::helper('gomage_feed')->__('Condition code is required.'));
        }
        if (!isset($data['operator'])) {
            throw new Mage_Core_Exception(Mage::helper('gomage_feed')->__('Condition operator is required.'));
        }
        return $data;
    }

}