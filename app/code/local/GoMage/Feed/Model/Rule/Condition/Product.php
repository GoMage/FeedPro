<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2018 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/license-agreement/  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.3.0
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Rule_Condition_Product extends Mage_CatalogRule_Model_Rule_Condition_Product
{

    /**
     * Rule condition SQL builder
     *
     * @var GoMage_Feed_Model_Rule_Condition_SqlBuilder
     */
    protected $_ruleResourceHelper;

    /**
     * Collect validated attributes
     *
     * @param Mage_Catalog_Model_Resource_Product_Collection $productCollection
     * @return Mage_CatalogRule_Model_Rule_Condition_Product
     */
    public function collectValidatedAttributes($productCollection)
    {
        $attribute = $this->getAttribute();
        if ($attribute == 'category_ids') {
            if (!$this->getRule()->getJoinedCategory()) {
                $productCollection->getSelect()->joinLeft(
                    array('ccp' => $productCollection->getResource()->getTable('catalog/category_product')),
                    'ccp.product_id = e.entity_id',
                    array()
                );
                $this->getRule()->setJoinedCategory(true);
            }
        } elseif ($attribute == 'qty') {
            if (!$this->getRule()->getJoinedQty()) {
                $productCollection->getSelect()->joinLeft(
                    array('csi' => $productCollection->getResource()->getTable('cataloginventory/stock_item')),
                    'csi.product_id = e.entity_id',
                    array()
                );
                $this->getRule()->setJoinedQty(true);
            }
        } elseif ($attribute !== 'type_id') {
            $attributes = $this->getRule()->getCollectedAttributes();
            $attributes[$attribute] = true;
            $this->getRule()->setCollectedAttributes($attributes);
            $productCollection->addAttributeToSelect($attribute, 'left');
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
        if (Mage::helper('catalog/product_flat')->isEnabled()) {
            return $this->prepareConditionSqlFlat();
        }

        $attribute = $this->getAttribute();
        $alias = 'at_' . $attribute;
        $field = 'value';

        /** @var $ruleResource GoMage_Feed_Model_Rule_Condition_SqlBuilder */
        $ruleResource = $this->getRuleResourceHelper();
        /** @var Mage_Catalog_Model_Resource_Product_Collection $productCollection */
        $productCollection = Mage::getResourceModel('catalog/product_collection');

        $value = $this->getValueParsed();
        $operator = $this->correctOperator($this->getOperator(), $this->getInputType());
        if ($attribute == 'category_ids') {
            $alias = 'ccp';
            $field = 'category_id';
            $value = $this->bindArrayOfIds($value);

            return $ruleResource->getOperatorCondition($alias . '.' . $field, $operator, $value);
        }

        if ($attribute == 'qty') {
            $alias = 'csi';
            $field = 'qty';

            return $ruleResource->getOperatorCondition($alias . '.' . $field, $operator, $value);
        }

        if ($productCollection->getEntity()->getAttribute($attribute)->isStatic()) {
            $alias = 'e';
            $field = $attribute;

            return $ruleResource->getOperatorCondition($alias . '.' . $field, $operator, $value);
        }

        if ($productCollection->getEntity()->getAttribute($attribute)->isScopeGlobal()) {
            return $ruleResource->getOperatorCondition($alias . '.' . $field, $operator, $value);
        }

        return $ruleResource->getOperatorCondition($alias . '.' . $field, $operator, $value) . ' OR ' .
            $ruleResource->getOperatorCondition($alias . '_default.' . $field, $operator, $value);
    }

    /**
     * Prepare sql where by condition
     *
     * @return string
     */
    public function prepareConditionSqlFlat()
    {
        $attribute = $this->getAttribute();
        $alias = 'e' ;
        $field = $attribute;

        /** @var $ruleResource GoMage_Feed_Model_Rule_Condition_SqlBuilder */
        $ruleResource = $this->getRuleResourceHelper();
        /** @var Mage_Catalog_Model_Resource_Product_Collection $productCollection */
        $productCollection = Mage::getResourceModel('catalog/product_collection');

        $value = $this->getValueParsed();
        $operator = $this->correctOperator($this->getOperator(), $this->getInputType());
        if ($attribute == 'category_ids') {
            $alias = 'ccp';
            $field = 'category_id';
            $value = $this->bindArrayOfIds($value);

            return $ruleResource->getOperatorCondition($alias . '.' . $field, $operator, $value);
        }

        if ($attribute == 'qty') {
            $alias = 'csi';
            $field = 'qty';

            return $ruleResource->getOperatorCondition($alias . '.' . $field, $operator, $value);
        }

        if ($productCollection->getEntity()->getAttribute($attribute)->isStatic()) {
            $alias = 'e';
            $field = $attribute;

            return $ruleResource->getOperatorCondition($alias . '.' . $field, $operator, $value);
        }

        if ($productCollection->getEntity()->getAttribute($attribute)->isScopeGlobal()) {
            return $ruleResource->getOperatorCondition($alias . '.' . $field, $operator, $value);
        }

        return $ruleResource->getOperatorCondition($alias . '.' . $field, $operator, $value) . ' OR ' .
            $ruleResource->getOperatorCondition($alias . '.' . $field, $operator, $value);
    }

    /**
     * Correct '==' and '!=' operators
     * Categories can't be equal because product is included categories selected by administrator and in their parents
     *
     * @param string $operator
     * @param string $inputType
     * @return string
     */
    public function correctOperator($operator, $inputType)
    {
        if ($inputType == 'category') {
            if ($operator == '==') {
                $operator = '{}';
            } elseif ($operator == '!=') {
                $operator = '!{}';
            }
        }

        return $operator;
    }

    /**
     * Prepare bind array of ids from string or array
     *
     * @param string|int|array $value
     * @return array
     */
    public function bindArrayOfIds($value)
    {
        if (!is_array($value)) {
            $value = explode(',', $value);
        }

        $value = array_map('trim', $value);
        $value = array_filter($value, 'is_numeric');

        if (empty($value)) {
            $value[] = 0;
        }

        return $value;
    }

    /**
     * Rule condition SQL builder getter
     *
     * @return GoMage_Feed_Model_Rule_Condition_SqlBuilder
     */
    public function getRuleResourceHelper()
    {
        if (!$this->_ruleResourceHelper) {
            $this->_ruleResourceHelper = Mage::getModel('gomage_feed/rule_condition_sqlBuilder');
        }

        return $this->_ruleResourceHelper;
    }

    /**
     * @return string
     */
    public function getAttributeName()
    {
        if ($this->getAttribute() == 'type_id') {
            $name = 'Product Type';
        } else {
            $name = $this->getAttributeOption($this->getAttribute()) ?:
                Mage::helper('gomage_feed')->__(ucwords($this->getAttribute()));
        }

        return $name;
    }

    /**
     * @return string
     */
    public function getInputType()
    {
        if ($this->getAttribute() == 'qty') {
            return 'numeric';
        }
        if ($this->getAttribute() === 'type_id') {
            return 'select';
        }

        return parent::getInputType();
    }

    /**
     * @inheritdoc
     */
    public function getValueElementType()
    {
        if ($this->getAttribute() === 'type_id') {
            return 'select';
        }

        return parent::getValueElementType();
    }

    /**
     * @inheritdoc
     */
    protected function _prepareValueOptions()
    {
        if ($this->getAttribute() === 'type_id') {
            $selectOptions = $this->getAllProductTypesOptionsArray();
            $this->setData('value_select_options', $selectOptions);

            $hashedOptions = array();
            foreach ($selectOptions as $o) {
                if (is_array($o['value'])) {
                    continue; // We cannot use array as index
                }
                $hashedOptions[$o['value']] = $o['label'];
            }
            $this->setData('value_option', $hashedOptions);
        }
        parent::_prepareValueOptions();

        return $this;
    }

    /**
     * @return array
     */
    private function getAllProductTypesOptionsArray()
    {
        $productTypesOptionsArray = [];

        foreach (Mage_Catalog_Model_Product_Type::getTypes() as $value => $availableType) {
            $productTypesOptionsArray[] = [
                'value' => $value,
                'label' => $availableType['label']
            ];
        }

        return $productTypesOptionsArray;
    }
}
