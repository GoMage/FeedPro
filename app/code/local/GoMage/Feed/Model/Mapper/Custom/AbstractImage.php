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
abstract class GoMage_Feed_Model_Mapper_Custom_AbstractImage implements GoMage_Feed_Model_Mapper_Custom_CustomMapperInterface
{
    /**
     * @return int
     */
    abstract public static function getImageIndex();

    /**
     * @param  Varien_Object $object
     * @return mixed
     */
    public function map(Varien_Object $object)
    {
        $width  = intval(Mage::getStoreConfig('gomage_feedpro/imagesettings/width'));
        $height = intval(Mage::getStoreConfig('gomage_feedpro/imagesettings/height'));
        $i      = 1;
        foreach ($object->getMediaGalleryImages() as $image) {
            if ($i == self::getImageIndex()) {
                if ($width || $height) {
                    return (string)Mage::helper('catalog/image')->init($object, 'image', $image->getPath())->resize($width, $height);
                } else {
                    return $image->getUrl();
                }
            }
            $i++;
        }
        return '';
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        return [];
    }
}