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
class GoMage_Feed_Model_Adminhtml_System_Config_Source_Operator
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $helper = Mage::helper('gomage_feed');
        return array(
            array('value' => GoMage_Feed_Model_Operator_OperatorInterface::EQUAL, 'label' => $helper->__('equal')),
            array('value' => GoMage_Feed_Model_Operator_OperatorInterface::NOT_EQUAL, 'label' => $helper->__('not equal')),
            array('value' => GoMage_Feed_Model_Operator_OperatorInterface::GREATER, 'label' => $helper->__('greater than')),
            array('value' => GoMage_Feed_Model_Operator_OperatorInterface::LESS, 'label' => $helper->__('less than')),
            array('value' => GoMage_Feed_Model_Operator_OperatorInterface::GREATER_EQUAL, 'label' => $helper->__('greater than or equal to')),
            array('value' => GoMage_Feed_Model_Operator_OperatorInterface::LESS_EQUAL, 'label' => $helper->__('less than or equal to')),
            array('value' => GoMage_Feed_Model_Operator_OperatorInterface::LIKE, 'label' => $helper->__('like')),
            array('value' => GoMage_Feed_Model_Operator_OperatorInterface::NOT_LIKE, 'label' => $helper->__('not like')),
        );
    }
}
