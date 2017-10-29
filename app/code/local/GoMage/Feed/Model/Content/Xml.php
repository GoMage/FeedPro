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
class GoMage_Feed_Model_Content_Xml extends GoMage_Feed_Model_Content_AbstractContent
{

    const BLOCK_PATTERN = '/\\{\\{block\b(.*?)\\}\\}(.*)\\{\\{\\/block\\}\\}/s';
    const ROW_PATTERN = '/\\{\\{var:(.+?)(\s.*?)?\\}\\}/s';
    const PARAMS_PATTERN = '/(.*?)\="(.*?)"/s';

    /**
     * @var string
     */
    protected $_entityType;

    /**
     * @var string
     */
    protected $_header;

    /**
     * @var string
     */
    protected $_footer;

    /**
     * @var string
     */
    protected $_block;


    public function __construct($arguments)
    {
        parent::__construct($arguments);

        $match = array();
        preg_match(self::BLOCK_PATTERN, $this->_content, $match);

        if (!isset($match[1]) || !isset($match[2])) {
            Mage::throwException(Mage::helper('gomage_feed')->__('Invalid XML Content.'));
        }

        preg_match_all(self::PARAMS_PATTERN, $match[1], $params);
        foreach ($params[1] as $_key => $param) {
            if (trim($param) == 'type') {
                $this->_entityType = trim($params[2][$_key]);
            }
        }

        $this->_block = trim($match[2]);
        list($this->_header, $this->_footer) = preg_split(self::BLOCK_PATTERN, $this->_content);
    }

    /**
     * @return GoMage_Feed_Model_Feed_Row_Collection
     */
    public function getRows()
    {
        if (is_null($this->_rows)) {
            $this->_rows = Mage::getModel('gomage_feed/feed_row_collection');
            $match       = array();
            preg_match_all(self::ROW_PATTERN, $this->_block, $match);

            if (isset($match[1])) {
                foreach ($match[1] as $key => $value) {
                    if ($this->getEntityType() == 'review') {
                        $type = GoMage_Feed_Model_Adminhtml_System_Config_Source_Field_TypeInterface::REVIEW;
                    } else {
                        $type = GoMage_Feed_Model_Adminhtml_System_Config_Source_Field_TypeInterface::ATTRIBUTE;
                    }
                    if (strpos($value, 'parent:') === 0) {
                        $type  = GoMage_Feed_Model_Adminhtml_System_Config_Source_Field_TypeInterface::PARENT_ATTRIBUTE;
                        $value = str_replace('parent:', '', $value);
                    }
                    $data = array(
                        'name'            => $match[0][$key],
                        'type'            => $type,
                        'attribute_value' => $value,
                    );

                    if (isset($match[2][$key]) && $match[2][$key]) {
                        preg_match_all(self::PARAMS_PATTERN, $match[2][$key], $params);
                        foreach ($params[1] as $_key => $param) {
                            $data[trim($param)] = trim($params[2][$_key]);
                        }
                    }

                    /** @var GoMage_Feed_Model_Feed_Row_Data $rowData */
                    $rowData = Mage::getModel('gomage_feed/feed_row_data', $data);

                    /** @var GoMage_Feed_Model_Feed_Row $row */
                    $row = Mage::getModel('gomage_feed/feed_row', $rowData);

                    $this->_rows->add($row);
                }
            }
        }
        return $this->_rows;
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        return $this->_header;
    }

    /**
     * @return string
     */
    public function getFooter()
    {
        return $this->_footer;
    }

    /**
     * @return string
     */
    public function getBlock()
    {
        return $this->_block;
    }

    /**
     * @return string
     */
    public function getEntityType()
    {
        return $this->_entityType;
    }
}
