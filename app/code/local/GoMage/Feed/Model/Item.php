<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2016 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 3.7.0
 * @since        Class available since Release 1.0
 */
class GoMage_Feed_Model_Item extends Mage_Rule_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('gomage_feed/item');
        $this->setIdFieldName('id');
    }

    public function save()
    {
        if (!$this->getFilename()) {
            $this->setFilename(preg_replace('/[^\w\d]/', '-', trim(strtolower($this->getName()))));
        }
        if ($this->getFilenameExt()) {
            $file_name = pathinfo($this->getFilename());
            $this->setFilename($file_name['filename']);
        }
        if ($id = Mage::getModel('gomage_feed/item')->load($this->getFilename(), 'filename')->getId()) {
            if ($id != $this->getId()) {
                throw new Mage_Core_Exception(Mage::helper('gomage_feed')->__('Filename "%s" exists', $this->getFilename()));
            }
        }
        return parent::save();
    }

    public function delete()
    {
        if ($this->getFileNameWithExt()) {
            $file_path = Mage::helper('gomage_feed/generator')->getBaseDir() . DS . $this->getFileNameWithExt();
            @unlink($file_path);
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
}