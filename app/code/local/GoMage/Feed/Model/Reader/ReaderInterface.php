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
interface GoMage_Feed_Model_Reader_ReaderInterface
{

    /**
     * @param  int $page
     * @param  int $limit
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function read($page = 1, $limit = 0);

    /**
     * @param Varien_Object $item
     * @return bool
     */
    public function isValidItem(Varien_Object $item);

}