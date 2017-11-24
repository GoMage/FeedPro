<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2017 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/license-agreement/  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.1.0
 * @since        Class available since Release 4.0.0
 */
abstract class GoMage_Feed_Model_Mapper_Custom_AbstractImage implements GoMage_Feed_Model_Mapper_Custom_CustomMapperInterface
{
    /**
     * @return int
     */
    public static function getImageIndex()
    {
        return 1;
    }

    /**
     * @param  Varien_Object $object
     * @return mixed
     */
    public function map(Varien_Object $object)
    {
        $width  = intval(Mage::getStoreConfig('gomage_feedpro/imagesettings/width'));
        $height = intval(Mage::getStoreConfig('gomage_feedpro/imagesettings/height'));
        $i      = 1;
        if (!$object->hasData('media_gallery_images')) {
            $object->getResource()->getAttribute('media_gallery')->getBackend()->afterLoad($object);
        }
        foreach ($object->getMediaGalleryImages() as $image) {
            if ($i == $this->getImageIndex()) {

                /** @var Mage_Catalog_Helper_Image $helper */
                $helper = Mage::helper('catalog/image')->init($object, 'image', $image->getFile());
                if ($width || $height) {
                    $helper->resize($width, $height);
                }
                return (string)$helper;
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
        return array('media_gallery');
    }
}