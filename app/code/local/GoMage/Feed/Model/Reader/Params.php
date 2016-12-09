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
 * @since        Class available since Release 1.0
 */

/**
 * Class Params
 *
 * @method array getAttributes()
 * @method int getStoreId()
 * @method int getVisibility()
 * @method bool getUseLayer()
 * @method bool getIsDisabled()
 * @method Mage_Rule_Model_Condition_Combine getConditions()
 */


class GoMage_Feed_Model_Reader_Params extends Varien_Object
{

    public function __construct(array $data = array())
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
        if (!isset($data['store_id']) || !$data['store_id']) {
            throw new Mage_Core_Exception(Mage::helper('gomage_feed')->__('Store is not specified.'));
        }
        $data['is_disabled'] = isset($data['is_disabled']) && boolval($data['is_disabled']);
        return $data;
    }
}
