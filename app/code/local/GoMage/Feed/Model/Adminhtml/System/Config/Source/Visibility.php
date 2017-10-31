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
 * @version      Release: 4.0.0
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Adminhtml_System_Config_Source_Visibility
{
    const NOT_USE = 0;
    const NOT_VISIBLE = 1;
    const CATALOG = 2;
    const SEARCH = 3;
    const CATALOG_SEARCH = 4;
    const ONLY_CATALOG = 5;
    const ONLY_SEARCH = 6;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $helper = Mage::helper('gomage_feed');
        return array(
            array('value' => self::CATALOG_SEARCH, 'label' => $helper->__('Catalog, Search')),
            array('value' => self::NOT_USE, 'label' => $helper->__('Not Use Option')),
            array('value' => self::NOT_VISIBLE, 'label' => $helper->__('Not Visible Individually')),
            array('value' => self::CATALOG, 'label' => $helper->__('Catalog')),
            array('value' => self::SEARCH, 'label' => $helper->__('Search')),
            array('value' => self::ONLY_CATALOG, 'label' => $helper->__('Only Catalog')),
            array('value' => self::ONLY_SEARCH, 'label' => $helper->__('Only Search')),
        );
    }

    /**
     * @param  int $visibility
     * @return array
     */
    public function getProductVisibility($visibility)
    {
        $result = array();
        switch ($visibility) {
            case self::CATALOG_SEARCH:
                $result = array(
                    Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_SEARCH,
                    Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG,
                    Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH
                );
                break;
            case self::NOT_VISIBLE:
                $result = array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE);
                break;
            case self::CATALOG:
                $result = array(
                    Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG,
                    Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
                break;
            case self::SEARCH:
                $result = array(
                    Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_SEARCH,
                    Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH
                );
                break;
            case self::ONLY_CATALOG:
                $result = array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG);
                break;
            case self::ONLY_SEARCH:
                $result = array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_SEARCH);
                break;
        }
        return $result;
    }
}

