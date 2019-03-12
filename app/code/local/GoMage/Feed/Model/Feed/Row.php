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
class GoMage_Feed_Model_Feed_Row
{

    /**
     * @var string
     */
    protected $_name;

    /**
     * @var int
     */
    protected $_limit;

    /**
     * @var GoMage_Feed_Model_Collection
     */
    protected $_outputs;

    /**
     * @var array
     */
    protected $_outputsArray;

    /**
     * @var GoMage_Feed_Model_Collection
     */
    protected $_fields;

    /**
     * @var
     */
    protected $_prefixValue;

    /**
     * @var
     */
    protected $_suffixValue;

    /**
     * GoMage_Feed_Model_Feed_Row constructor.
     * @param GoMage_Feed_Model_Feed_Row_Data $rowData
     */
    public function __construct(GoMage_Feed_Model_Feed_Row_Data $rowData)
    {
        $this->_name  = $rowData->getName();
        $this->_limit = (int)$rowData->getLimit();

        if ($rowData->getPrefixType() == 'static') {
            $this->_prefixValue = $rowData->getPrefixValue();
        } else {
            $this->_prefixValue = $rowData->getAttributePrefixValue();
        }
        if ($rowData->getSuffixType() == 'static') {
            $this->_suffixValue = $rowData->getSuffixValue();
        } else {
            $this->_suffixValue = $rowData->getAttributeSuffixValue();
        }

        /** @var GoMage_Feed_Model_Output_Factory $outputFactory */
        $outputFactory = Mage::getSingleton('gomage_feed/output_factory');

        $this->_outputs = Mage::getModel('gomage_feed/collection');
        foreach ($rowData->getOutputType() as $value) {
            /** @var GoMage_Feed_Model_Output_OutputInterface $output */
            $output = $outputFactory->get($value);
            $this->_outputs->add($output);
        }
        $this->_outputsArray = $rowData->getOutputType();

        $this->_fields = Mage::getModel('gomage_feed/collection');
        foreach ($rowData->getFields() as $data) {
            /** @var GoMage_Feed_Model_Feed_Field $field */
            $field = Mage::getModel('gomage_feed/feed_field', $data);
            $this->_fields->add($field);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param Varien_Object $object
     * @return bool|mixed|string
     */
    public function map(Varien_Object $object)
    {
        $array = array_map(function (GoMage_Feed_Model_Feed_Field $field) use ($object) {
            return $field->map($object);
        }, iterator_to_array($this->_fields)
        );

        if (in_array(GoMage_Feed_Model_Output_OutputInterface::DATE_TIME, $this->_outputsArray) ||
            in_array(GoMage_Feed_Model_Output_OutputInterface::FLOATS, $this->_outputsArray) ||
            in_array(GoMage_Feed_Model_Output_OutputInterface::INTEGER, $this->_outputsArray)) {
            if ($this->_prefixValue && $this->_suffixValue) {
                $array[1] = $this->format($array[1]);
            } elseif ($this->_prefixValue) {
                $array[1] = $this->format($array[1]);
            } elseif ($this->_suffixValue) {
                $array[0] = $this->format($array[0]);
            } else {
                $array[0] = $this->format($array[0]);
            }

            if ($this->_limit) {
                return substr(implode('', $array), 0, $this->_limit);
            }

            return implode('', $array);
        }

        $formatedValue = $this->format(implode('', $array));

        if ($this->_limit) {
            return substr($formatedValue, 0, $this->_limit);
        }

        return $formatedValue;
    }

    /**
     * @param $value
     * @return mixed
     */
    protected function format($value)
    {
        foreach ($this->_outputs as $output) {
            /** @var GoMage_Feed_Model_Output_OutputInterface $output */
            $value = $output->format($value);
        }
        return $value;
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        $attributes = array();
        /** @var GoMage_Feed_Model_Feed_Field $field */
        foreach ($this->_fields as $field) {
            $attributes = array_merge($attributes, $field->getUsedAttributes());
        }
        return $attributes;
    }
}
