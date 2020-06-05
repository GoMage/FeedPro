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
interface GoMage_Feed_Model_Content_ContentInterface
{
    /**
     * @return GoMage_Feed_Model_Feed_Row_Collection
     */
    public function getRows();

    /**
     * @return string
     */
    public function getEntityType();
}