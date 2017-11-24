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
 * @version      Release: 4.1.0
 * @since        Class available since Release 1.0
 */
class GoMage_Feed_Model_Item extends Mage_Rule_Model_Abstract
{

    /** @var array */
    protected $_contentModifiedFields = array(
        'store_id',
        'type',
        'filename',
        'content',
        'show_headers',
        'enclosure',
        'delimiter',
        'use_layer',
        'use_disabled',
        'visibility',
        'addition_header',
        'filename_ext',
        'delimiter_prefix',
        'delimiter_sufix',
        'conditions_serialized',
        'generate_type',
    );

    /**
     * {@inheritdoc}
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('gomage_feed/item');
        $this->setIdFieldName('id');
    }

    /**
     * {@inheritdoc}
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        if (!$this->getFilename()) {
            $this->setFilename(preg_replace('/[^\w\d]/', '-', trim(strtolower($this->getName()))));
        }
        if ($this->getFilenameExt()) {
            $file_name = pathinfo($this->getFilename());
            $this->setFilename($file_name['filename']);
        }
        if ($id = Mage::getModel('gomage_feed/item')->load($this->getFilename(), 'filename')->getId()) {
            if ($id != $this->getId()) {
                Mage::throwException(Mage::helper('gomage_feed')->__('Filename "%s" exists', $this->getFilename()));
            }
        }
        if ($this->_isContentModified() && ($this->getData('generate_type') == GoMage_Feed_Model_Adminhtml_System_Config_Source_Generate::CHANGED)) {
            $this->setData('generated_at', '0000-00-00 00:00:00');
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function delete()
    {
        if ($this->getFileNameWithExt()) {
            $file_path = Mage::helper('gomage_feed/generator')->getBaseDir() . DS . $this->getFileNameWithExt();
            unlink($file_path);
        }
        return parent::delete();
    }

    /**
     * @return string
     */
    public function getFileNameWithExt()
    {
        $filename = $this->getFilename();
        if (strpos($this->getFilename(), '.') === false) {
            if ($this->getFilenameExt()) {
                $filename .= '.' . $this->getFilenameExt();
            }
        }
        return $filename;
    }

    public function getUrl()
    {
        $file_path = sprintf('productsfeed/%s', $this->getFileNameWithExt());
        if (file_exists(Mage::getBaseDir('media') . '/' . $file_path)) {
            return Mage::app()->getStore($this->getStoreId())->getBaseUrl('media', false) . $file_path;
        }
        return '';
    }

    /**
     * Getter for rule combine conditions instance
     *
     * @return GoMage_Feed_Model_Rule_Condition_Combine
     */
    public function getConditionsInstance()
    {
        return Mage::getModel('gomage_feed/rule_condition_combine');
    }

    /**
     * Getter for rule actions collection instance
     *
     * @return Mage_Rule_Model_Action_Collection
     */
    public function getActionsInstance()
    {
        return Mage::getModel('catalogrule/rule_action_collection');
    }

    /**
     * @return bool
     */
    protected function _isContentModified()
    {
        if ($this->getId()) {
            $feed = Mage::getModel('gomage_feed/item')->load($this->getId());
            foreach ($this->_contentModifiedFields as $field) {
                if ($feed->getData($field) != $this->getData($field)) {
                    return true;
                }
            }
        }
        return false;
    }

}