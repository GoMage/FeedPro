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
class GoMage_Feed_Model_Mapper_Custom_Image4 extends GoMage_Feed_Model_Mapper_Custom_AbstractImage
{
    /**
     * @return int
     */
    public static function getImageIndex()
    {
        return 4;
    }

    /**
     * @return string
     */
    public static function getLabel()
    {
        return Mage::helper('gomage_feed')->__('Image %s', self::getImageIndex());
    }

}