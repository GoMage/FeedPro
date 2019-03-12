<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2019 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/license-agreement/  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.3.1
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Operator_Factory
{
    /**
     * @var array
     */
    protected $_operators = [
        GoMage_Feed_Model_Operator_OperatorInterface::EQUAL         => 'gomage_feed/operator_equal',
        GoMage_Feed_Model_Operator_OperatorInterface::NOT_EQUAL     => 'gomage_feed/operator_notEqual',
        GoMage_Feed_Model_Operator_OperatorInterface::GREATER       => 'gomage_feed/operator_greater',
        GoMage_Feed_Model_Operator_OperatorInterface::LESS          => 'gomage_feed/operator_less',
        GoMage_Feed_Model_Operator_OperatorInterface::GREATER_EQUAL => 'gomage_feed/operator_greaterEqual',
        GoMage_Feed_Model_Operator_OperatorInterface::LESS_EQUAL    => 'gomage_feed/operator_lessEqual',
        GoMage_Feed_Model_Operator_OperatorInterface::LIKE          => 'gomage_feed/operator_like',
        GoMage_Feed_Model_Operator_OperatorInterface::NOT_LIKE      => 'gomage_feed/operator_notLike',
    ];

    /**
     * @param  int $operator
     * @return GoMage_Feed_Model_Operator_OperatorInterface
     */
    public function get($operator)
    {
        return Mage::getSingleton($this->_operators[(int)$operator]);
    }

}
