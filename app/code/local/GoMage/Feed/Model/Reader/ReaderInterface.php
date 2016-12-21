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
interface GoMage_Feed_Model_Reader_ReaderInterface
{

    /**
     * @param  int $page
     * @param  int $limit
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function read($page, $limit);

    /**
     * @param Varien_Object $item
     * @return bool
     */
    public function isValidItem(Varien_Object $item);

}