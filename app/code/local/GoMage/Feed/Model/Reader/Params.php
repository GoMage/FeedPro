<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2019 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/license-agreement/  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.3.1
 * @since        Class available since Release 4.0.0
 */

/**
 * Class Params
 *
 * @method array getAttributes()
 * @method int getStoreId()
 * @method int getVisibility()
 * @method bool getUseLayer()
 * @method bool getIsDisabled()
 * @method GoMage_Feed_Model_Rule_Condition_Combine getConditions()
 * @method int getGenerateType()
 * @method string getGeneratedAt()
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
            Mage::throwException(Mage::helper('gomage_feed')->__('Store is not specified.'));
        }
        $data['is_disabled'] = isset($data['is_disabled']) && boolval($data['is_disabled']);
        return $data;
    }
}
