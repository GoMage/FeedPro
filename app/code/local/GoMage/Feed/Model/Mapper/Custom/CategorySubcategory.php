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
class GoMage_Feed_Model_Mapper_Custom_CategorySubcategory implements GoMage_Feed_Model_Mapper_Custom_CustomMapperInterface
{

    /**
     * @param  Varien_Object $object
     * @return mixed
     */
    public function map(Varien_Object $object)
    {
        $categoryIds = $object->getCategoryIds();
        if (count($categoryIds)) {
            $categoryId = max($categoryIds);
            $category   = Mage::getModel('catalog/category')->load($categoryId);
            $categories = $category->getParentCategories();
            return implode(' > ', array_map(function ($category) {
                    return $category->getName();
                }, $categories
                )
            );
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

    /**
     * @return string
     */
    public static function getLabel()
    {
        return Mage::helper('gomage_feed')->__('Category > SubCategory');
    }

}