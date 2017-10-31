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
 * @version      Release: 4.0.0
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Rule_Condition_SqlBuilder
{
    /**
     * Database adapter
     *
     * @var Varien_Db_Adapter_Interface
     */
    protected $_adapter;

    /**
     * Constructor
     *
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        $this->_adapter = isset($config['adapter'])
            ? $config['adapter']
            : Mage::getSingleton('core/resource')->getConnection(Mage_Core_Model_Resource::DEFAULT_READ_RESOURCE);
    }

    /**
     * Convert operator for sql where
     *
     * @param string $field
     * @param string $operator
     * @param string|array $value
     * @return string
     */
    public function getOperatorCondition($field, $operator, $value)
    {
        switch ($operator) {
            case '!=':
            case '>=':
            case '<=':
            case '>':
            case '<':
                $selectOperator = sprintf('%s?', $operator);
                break;
            case '{}':
            case '!{}':
                if (preg_match('/^.*(category_id)$/', $field) && is_array($value)) {
                    $selectOperator = ' IN (?)';
                } else {
                    $selectOperator = ' LIKE ?';
                }
                if (substr($operator, 0, 1) == '!') {
                    $selectOperator = ' NOT' . $selectOperator;
                }
                break;

            case '[]':
            case '![]':
            case '()':
            case '!()':
                $selectOperator = 'FIND_IN_SET(?,' . $this->_adapter->quoteIdentifier($field) . ')';
                if (substr($operator, 0, 1) == '!') {
                    $selectOperator = 'NOT ' . $selectOperator;
                }
                break;

            default:
                $selectOperator = '=?';
                break;
        }
        $field = $this->_adapter->quoteIdentifier($field);

        if (is_array($value) && in_array($operator, array('==', '!=', '>=', '<=', '>', '<', '{}', '!{}'))) {
            $results = array();
            foreach ($value as $v) {
                $results[] = $this->_adapter->quoteInto("{$field}{$selectOperator}", $v);
            }
            $result = implode(' AND ', $results);
        } elseif (in_array($operator, array('()', '!()', '[]', '![]'))) {
            if (!is_array($value)) {
                $value = array($value);
            }

            $results = array();
            foreach ($value as $v) {
                $results[] = $this->_adapter->quoteInto("{$selectOperator}", $v);
            }
            $result = implode(in_array($operator, array('()', '!()')) ? ' OR ' : ' AND ', $results);
        } elseif (is_string($value) && in_array($operator, array('{}', '!{}'))) {
            $value  = '%' . $value . '%';
            $result = $this->_adapter->quoteInto("{$field}{$selectOperator}", $value);
        } else {
            $result = $this->_adapter->quoteInto("{$field}{$selectOperator}", $value);
        }
        return $result;
    }
}
