<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2012 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 3.7.0
 * @since        Class available since Release 3.6
 */
class GoMage_Feed_Adminhtml_Gomage_Feed_AttributesController extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('catalog/gomage_feed')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Feed Manager'), Mage::helper('adminhtml')->__('Feed Manager'));

        return $this;
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/gomage_feed/gomage_feed_attributes');
    }

    public function indexAction()
    {
        $this->_initAction();
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_initAction();

        if ($data = Mage::getSingleton('core/session')->getCustomAttributeData()) {
            Mage::register('gomage_attribute', Mage::getModel('gomage_feed/attribute')->addData($data));
            Mage::getSingleton('core/session')->setCustomAttributeData(null);
        }

        $this->_addContent($this->getLayout()->createBlock('gomage_feed/adminhtml_attributes_edit'))
            ->_addLeft($this->getLayout()->createBlock('gomage_feed/adminhtml_attributes_edit_tabs'));

        $this->renderLayout();

    }

    public function editAction()
    {
        $this->_initAction();
        if ($id = $this->getRequest()->getParam('id')) {
            Mage::register('gomage_attribute', Mage::getModel('gomage_feed/attribute')->load($id));
        }
        $this->_addContent($this->getLayout()->createBlock('gomage_feed/adminhtml_attributes_edit'))
            ->_addLeft($this->getLayout()->createBlock('gomage_feed/adminhtml_attributes_edit_tabs'));

        $this->renderLayout();
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            try {
                $id    = $this->getRequest()->getParam('id');
                $model = Mage::getModel('gomage_feed/attribute');

                if (isset($data['option'])) {
                    unset($data['option']['{{row_id}}']);
                    $data['option'] = $this->_prepareData($data['option']);
                    $data['data']   = Zend_Json::encode($data['option']);
                }

                unset($data['option']);

                $model->setData($data)->setId($id)->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('core')->__('Dynamic attribute was successfully saved'));

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return false;
                }

            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('core/session')->setCustomAttributeData($data);

                if ($model->getId() > 0) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                } else {
                    $this->_redirect('*/*/new', array('type' => $model->getType()));
                }
                return false;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('core')->__('Can’t save data'));
                Mage::getSingleton('core/session')->setCustomAttributeData($data);

                if ($model->getId() > 0) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                } else {
                    $this->_redirect('*/*/new', array('type' => $model->getType()));
                }
                return false;
            }
            $this->_redirect('*/*/');
        }
    }

    /**
     * @param  array $data
     * @return array
     */
    protected function _prepareData(array $data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->_prepareData($value);
            }
        }
        return array_merge($data, []);
    }

    public function deleteAction()
    {
        if ($id = intval($this->getRequest()->getParam('id'))) {
            $this->_deleteItems(array($id));
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        if ($ids = $this->getRequest()->getParam('id')) {
            if (is_array($ids) && !empty($ids)) {
                $this->_deleteItems($ids);
            }
        }
        $this->_redirect('*/*/');
    }

    protected function _deleteItems($ids)
    {
        if (is_array($ids) && !empty($ids)) {
            $model = Mage::getModel('gomage_feed/attribute');
            foreach ($ids as $id) {
                $model->setId($id)->delete();
            }
        }
    }

    public function attributeValueAction()
    {
        $result           = array();
        $result['values'] = array();
        if ($code = $this->getRequest()->getParam('code')) {
            $attribute = Mage::getModel('catalog/product')->getResource()->getAttribute($code);
            if (($attribute && in_array($attribute->getFrontendInput(), array('select', 'multiselect'))) ||
                ($code == 'product_type')
            ) {
                if ($code == 'product_type') {
                    $result['values'] = Mage_Catalog_Model_Product_Type::getOptions();
                } else {
                    $result['values'] = $attribute->getSource()->getAllOptions();
                }
            }
        }
        $this->getResponse()->setBody(Zend_Json::encode($result));
    }

    public function mappingexportAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $model = Mage::getModel('gomage_feed/attribute')->load($id);
            $this->getResponse()->setBody($model->getData('data'));
            $this->getResponse()->setHeader('Content-Type', 'text');
            $this->getResponse()->setHeader('Content-Disposition', 'attachment; filename="mapping-export-' . basename($model->getCode()) . '.txt";');
        }
    }

    public function mappingimportAction()
    {
        if (($post = $this->getRequest()->getPost()) && ($id = $this->getRequest()->getParam('id')) && isset($_FILES['mappingfile']) && $_FILES['mappingfile']) {
            try {
                $data       = file_get_contents($_FILES['mappingfile']['tmp_name']);
                $array_data = json_decode($data);
                if (empty($array_data)) {
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('core')->__('Empty or Invalid data file'));
                } else {
                    Mage::getModel('gomage_feed/attribute')->load($id)->setData('data', $data)->save();
                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('core')->__('Data successfully imported'));
                }
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('core')->__('Unknown error'));
            }
            return $this->_redirect('*/*/edit', array('id' => $id, 'tab' => 'data_section'));
        }

        $this->_initAction();
        if ($id = $this->getRequest()->getParam('id')) {
            Mage::register('gomage_attribute', Mage::getModel('gomage_feed/attribute')->load($id));
            $this->_addContent($this->getLayout()->createBlock('gomage_feed/adminhtml_items_mappingimport'))
                ->_addLeft($this->getLayout()->createBlock('gomage_feed/adminhtml_items_mappingimport_tabs'));

        }
        $this->renderLayout();
    }

}