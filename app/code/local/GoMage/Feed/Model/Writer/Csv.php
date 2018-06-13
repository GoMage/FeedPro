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
 * @version      Release: 4.1.0
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Writer_Csv extends GoMage_Feed_Model_Writer_AbstractWriter
{
    /**
     * @var string
     */
    protected $_delimiter;

    /**
     * @var string
     */
    protected $_enclosure;

    /**
     * @var bool
     */
    protected $_isHeader;

    /**
     * @var array
     */
    protected $_headerCols = null;

    /**
     * @var bool
     */
    protected $_removeLb;

    /**
     * @var GoMage_Feed_Model_Output_OutputInterface
     */
    protected $_removeLbOutput;

    public function __construct($arguments)
    {
        parent::__construct($arguments['fileName']);
        $this->_delimiter = $arguments['delimiter'];
        $this->_enclosure = $arguments['enclosure'];
        $this->_isHeader  = $arguments['isHeader'];
        $this->_removeLb  = $arguments['removeLb'];
        if ($arguments['additionHeader']) {
            fwrite($this->_fileHandler, $arguments['additionHeader']);
        }
        /** @var GoMage_Feed_Model_Output_Factory $outputFactory */
        $outputFactory         = Mage::getSingleton('gomage_feed/output_factory');
        $this->_removeLbOutput = $outputFactory->get(GoMage_Feed_Model_Output_OutputInterface::REMOVE_LINE_BREAK);
    }

    /**
     * Set column names.
     *
     * @param array $headerColumns
     * @throws Mage_Core_Exception
     */
    public function setHeaderCols(array $headerColumns)
    {
        if (null !== $this->_headerCols) {
            Mage::throwException(Mage::helper('gomage_feed')->__('The header column names are already set.'));
        }
        $this->_headerCols = array();
        foreach ($headerColumns as $columnName) {
            $this->_headerCols[$columnName] = false;
        }
        if ($this->_isHeader) {
            $this->write(array_combine(array_keys($this->_headerCols), array_keys($this->_headerCols)));
        }
    }

    /**
     * @param  array $data
     * @return array
     */
    protected function _prepareData(array $data)
    {
        if ($this->_removeLb) {
            $output = $this->_removeLbOutput;
            $data   = array_map(function ($value) use ($output) {
                return $output->format($value);
            }, $data
            );
        }
        return $data;
    }

    /**
     * @param array $data
     */
    public function write(array $data)
    {
        if (null === $this->_headerCols) {
            $this->setHeaderCols(array_keys($data));
        }

        $data = $this->_prepareData($data);
        $fields = array_merge($this->_headerCols, array_intersect_key($data, $this->_headerCols));

        if ($this->_enclosure != '') {
            fputcsv(
                $this->_fileHandler,
                $fields,
                $this->_delimiter,
                $this->_enclosure
            );
        } else {
            foreach ($fields as &$field) {
                if (strpos($field, $this->_delimiter) !== false) {
                    $field = $this->applyEnclosure($field, $this->_delimiter);
                }
                if (strpos($field, "\\") !== false) {
                    $field = $this->applyEnclosure($field, "\\");
                }
                if (strpos($field, "\n") !== false) {
                    $field = $this->applyEnclosure($field, "\n");
                }
                if (strpos($field, "\r") !== false) {
                    $field = $this->applyEnclosure($field, "\r");
                }
                if (strpos($field, "\t") !== false) {
                    $field = $this->applyEnclosure($field, "\t");
                }

                $field = str_replace('   ', ' ', $field);
            }

            fwrite($this->_fileHandler, implode($this->_delimiter, $fields) . "\n");
        }
    }

    /**
     * @param $field
     * @param $symbol
     *
     * @return string
     */
    private function applyEnclosure($field, $symbol)
    {
        $enclosure = ' ';
        $fieldPieces = explode($symbol, $field);
        $field = '';

        foreach ($fieldPieces as $key => $piece) {
            $field .= $piece;

            if (count($fieldPieces) - 1 != $key) {
                $field .= $enclosure . $symbol . $enclosure;
            }
        }

        return $field;
    }
}
