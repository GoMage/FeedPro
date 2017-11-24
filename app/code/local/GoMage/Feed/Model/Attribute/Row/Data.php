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
 * @version      Release: 4.1.0
 * @since        Class available since Release 4.0.0
 */

/**
 * Class Data
 *
 * @method array getConditions()
 * @method string getType()
 * @method mixed getValue()
 */
class GoMage_Feed_Model_Attribute_Row_Data extends Varien_Object
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
        if (!isset($data['condition']) || !$data['condition']) {
            Mage::throwException(Mage::helper('gomage_feed')->__('Conditions are not specified.'));
        }
        $data['conditions'] = $data['condition'];
        unset($data['condition']);
        if (!isset($data['value_type']) || !$data['value_type']) {
            Mage::throwException(Mage::helper('gomage_feed')->__('Type is required field.'));
        }
        $data['type'] = $data['value_type'];
        unset($data['value_type']);

        return $data;
    }
}
