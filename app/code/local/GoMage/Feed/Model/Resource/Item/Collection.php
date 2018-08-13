<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2017 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.2.0
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Resource_Item_Collection extends Mage_Rule_Model_Resource_Rule_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('gomage_feed/item');
    }

}