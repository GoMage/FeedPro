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
class GoMage_Feed_Model_Rule_Condition_Product extends Mage_CatalogRule_Model_Rule_Condition_Product
{

    /**
     * Collect validated attributes
     *
     * @param Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $productCollection
     * @return Mage_CatalogRule_Model_Rule_Condition_Product
     */
    public function collectValidatedAttributes($productCollection)
    {
        $attribute = $this->getAttribute();
        if ('category_ids' != $attribute) {
            $attributes             = $this->getRule()->getCollectedAttributes();
            $attributes[$attribute] = true;
            $this->getRule()->setCollectedAttributes($attributes);
            $productCollection->addAttributeToSelect($attribute, 'left');
        } else {
            if (!$this->getRule()->getJoinedCategory()) {
                $productCollection->getSelect()->joinLeft(
                    array('ccp' => $productCollection->getResource()->getTable('catalog/category_product')),
                    'ccp.product_id = e.entity_id',
                    array()
                );
                $this->getRule()->setJoinedCategory(true);
            }
        }

        return $this;
    }

    /**
     * Prepare sql where by condition
     *
     * @return string
     */
    public function prepareConditionSql()
    {
        $attribute = $this->getAttribute();
        $alias     = 'at_' . $attribute;
        $field     = 'value';

        /** @var $ruleResource Mage_Rule_Model_Resource_Rule_Condition_SqlBuilder */
        $ruleResource = $this->getRuleResourceHelper();
        /** @var Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $productCollection */
        $productCollection = Mage::getResourceModel('catalog/product_collection');

        $value    = $this->getValueParsed();
        $operator = $this->correctOperator($this->getOperator(), $this->getInputType());
        if ($attribute == 'category_ids') {
            $alias = 'ccp';
            $field = 'category_id';
            $value = $this->bindArrayOfIds($value);

            return $ruleResource->getOperatorCondition($alias . '.' . $field, $operator, $value);
        }

        if ($productCollection->getEntity()->getAttribute($attribute)->isStatic()) {
            $alias = 'e';
            $field = $attribute;
        }

        return $ruleResource->getOperatorCondition($alias . '.' . $field, $operator, $value);
    }

}
