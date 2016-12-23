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
 * @version      Release: 4.0.0
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Rule_Condition_Combine extends Mage_Rule_Model_Condition_Combine
{
    public function __construct()
    {
        parent::__construct();
        $this->setType('gomage_feed/rule_condition_combine');
    }

    public function getNewChildSelectOptions()
    {
        $productCondition  = Mage::getModel('gomage_feed/rule_condition_product');
        $productAttributes = $productCondition->loadAttributeOptions()->getAttributeOption();
        $attributes        = array();
        foreach ($productAttributes as $code => $label) {
            $attributes[] = array('value' => 'gomage_feed/rule_condition_product|' . $code, 'label' => $label);
        }
        $conditions = parent::getNewChildSelectOptions();
        $conditions = array_merge_recursive($conditions, array(
                array('value' => 'gomage_feed/rule_condition_combine', 'label' => Mage::helper('gomage_feed')->__('Conditions Combination')),
                array('label' => Mage::helper('gomage_feed')->__('Product Attribute'), 'value' => $attributes),
            )
        );
        return $conditions;
    }

    public function collectValidatedAttributes($productCollection)
    {
        foreach ($this->getConditions() as $condition) {
            $condition->collectValidatedAttributes($productCollection);
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
        $wheres = array();
        foreach ($this->getConditions() as $condition) {
            /** @var $condition Mage_Rule_Model_Condition_Abstract */
            $wheres[] = $condition->prepareConditionSql();
        }

        if (empty($wheres)) {
            return '';
        }
        $delimiter = $this->getAggregator() == "all" ? ' AND ' : ' OR ';
        return ' (' . implode($delimiter, $wheres) . ') ';
    }

}
