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
class GoMage_Feed_Model_Mapper_Custom_FinalPrice implements GoMage_Feed_Model_Mapper_Custom_CustomMapperInterface
{

    /**
     * @param  Varien_Object $object
     * @return mixed
     */
    public function map(Varien_Object $object)
    {
        $store = Mage::app()->getStore($object->getStoreId());
        return $store->convertPrice($object->getFinalPrice(), false, false);
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        return ['store_id'];
    }

    /**
     * @return string
     */
    public static function getLabel()
    {
        return Mage::helper('gomage_feed')->__('Final Price');
    }

}