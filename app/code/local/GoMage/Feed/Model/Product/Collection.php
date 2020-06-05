<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2015 GoMage.com (http://www.gomage.com)
 * @author       GoMage.com
 * @license      http://www.gomage.com/licensing  Single domain license
 * @terms of use http://www.gomage.com/terms-of-use
 * @version      Release: 4.3.2
 * @since        Class available since Release 2.0
 */
class GoMage_Feed_Model_Product_Collection extends Mage_Catalog_Model_Resource_Product_Collection
{
    protected function _productLimitationPrice($joinLeft = false)
    {
        $joinLeft = true;
        return parent::_productLimitationPrice($joinLeft);
    }
}
