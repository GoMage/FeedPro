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
        if (!isset($data['attribute_code']) || !$data['attribute_code']) {
            Mage::throwException(Mage::helper('gomage_feed')->__('Condition code is required.'));
        }
        $data['code'] = $data['attribute_code'];
        unset($data['attribute_code']);
        if (!isset($data['condition'])) {
            Mage::throwException(Mage::helper('gomage_feed')->__('Condition operator is required.'));
        }
        $data['operator'] = $data['condition'];
        unset($data['condition']);
        return $data;
    }

}
