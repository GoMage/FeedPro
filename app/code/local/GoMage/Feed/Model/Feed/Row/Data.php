<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2020 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/license-agreement/  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.3.2
 * @since        Class available since Release 4.0.0
 */

/**
 * Class GoMage_Feed_Model_Feed_Row_Data
 *
 * @method string getName()
 * @method string getLimit()
 * @method array getOutputType()
 */
class GoMage_Feed_Model_Feed_Row_Data extends Varien_Object
{

    public function __construct(array $data = array())
    {
        $data = $this->validate($data);
        parent::__construct($data);
    }

    /**
     * @param  array $data
     * @return array
     * @throws Mage_Core_Exception
     */
    protected function validate(array $data)
    {
        if (!isset($data['name']) || !$data['name']) {
            Mage::throwException(Mage::helper('gomage_feed')->__('Name is required field.'));
        }
        if (isset($data['format'])) {
            $data['output_type'] = $data['format'];
            unset($data['format']);
        }
        if (!isset($data['output_type'])) {
            $data['output_type'] = array();
        }
        if (is_string($data['output_type'])) {
            $data['output_type'] = explode(',', $data['output_type']);
        }
        return $data;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        $data = array();
        if ($this->getData('prefix_value') || $this->getData('attribute_prefix_value')) {
            $data[] = array(
                'type'  => $this->getData('prefix_type'),
                'value' => $this->getData('attribute_prefix_value') ?: $this->getData('prefix_value')
            );
        }
        $data[] = array(
            'type'  => $this->getData('type'),
            'value' => $this->getData('attribute_value') ?: $this->getData('static_value')
        );
        if ($this->getData('suffix_value') || $this->getData('attribute_suffix_value')) {
            $data[] = array(
                'type'  => $this->getData('suffix_type'),
                'value' => $this->getData('attribute_suffix_value') ?: $this->getData('suffix_value')
            );
        }

        return $data;
    }

}
