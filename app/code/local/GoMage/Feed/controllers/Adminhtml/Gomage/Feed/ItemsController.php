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
 * @since        Class available since Release 3.6
 */
class GoMage_Feed_Adminhtml_Gomage_Feed_ItemsController extends Mage_Adminhtml_Controller_Action
{

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/gomage_feed/gomage_feed_items');
    }

    protected function _initAction()
    {
        $this->loadLayout()->_setActiveMenu('catalog/gomage_feed')->_addBreadcrumb(Mage::helper('adminhtml')->__('Feed Manager'), Mage::helper('adminhtml')->__('Feed Manager'));
        return $this;
    }

    public function indexAction()
    {
        $this->_initAction();
        $this->renderLayout();
    }

    public function mappingimportAction()
    {

        if (($post = $this->getRequest()->getPost()) && ($id = $this->getRequest()->getParam('id')) && isset($_FILES['mappingfile']) && $_FILES['mappingfile']) {

            try {

                $data = file_get_contents($_FILES['mappingfile']['tmp_name']);

                $array_data = json_decode($data);

                if (empty($array_data)) {

                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('core')->__('Empty or Invalid data file'));

                } else {

                    Mage::getModel('gomage_feed/item')->load($id)->setContent($data)->save();
                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('core')->__('Data successfully imported'));

                }

            } catch (Exception $e) {

                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('core')->__('Unknown error'));

            }

            return $this->_redirect('*/*/edit', array('id' => $id, 'tab' => 'content_section'));
        }

        $this->_initAction();
        if ($id = $this->getRequest()->getParam('id')) {
            Mage::register('gomage_feed', Mage::getModel('gomage_feed/item')->load($id));
            $this->_addContent($this->getLayout()->createBlock('gomage_feed/adminhtml_items_mappingimport'))->_addLeft($this->getLayout()->createBlock('gomage_feed/adminhtml_items_mappingimport_tabs'));

        }
        $this->renderLayout();
    }

    public function ajaxuploadAction()
    {
        $result = array();
        try {
            $uploader = new Varien_File_Uploader('file');
            $uploader->setAllowRenameFiles(false);
            $uploader->setFilesDispersion(false);
            $path = Mage::getBaseDir('media') . DS . 'productsfeed' . DS . 'tmp';
            if (!file_exists($path)) {
                mkdir($path);
                chmod($path, 0777);
            }
            $result = $uploader->save($path, $_FILES['file']['name']);
        } catch (Exception $e) {
            $result = array('error' => $e->getMessage(), 'errorcode' => $e->getCode());
        }
        $this->getResponse()->setBody(Zend_Json::encode($result));
    }

    public function mappingimportsectionAction()
    {

        $result          = array();
        $result['error'] = false;

        if (($file = $this->getRequest()->getParam('file')) && ($id = $this->getRequest()->getParam('id'))) {

            try {

                if ($this->getRequest()->getParam('section') == 1) {
                    $data = file_get_contents(Mage::getBaseDir('media') . DS . 'productsfeed' . DS . 'examples' . DS . $file);
                } else {
                    $data = file_get_contents(Mage::getBaseDir('media') . DS . 'productsfeed' . DS . 'tmp' . DS . $file);
                }

                $array_data = json_decode($data);

                if (empty($array_data)) {

                    $result['error']      = true;
                    $result['error_text'] = Mage::helper('core')->__('Empty or Invalid data file');

                } else {

                    $feed = Mage::getModel('gomage_feed/item')->load($id)->setContent($data);
                }

            } catch (Exception $e) {

                $result['error']      = true;
                $result['error_text'] = Mage::helper('core')->__('Unknown error');

            }

            if (!$result['error']) {
                $result['feed'] = $this->getLayout()->createBlock('adminhtml/template')->setData('feed', $feed)->setTemplate('gomage/feed/item/edit/content/mapping.phtml')->toHtml();
            }
            echo Zend_Json::encode($result);
        }

    }

    public function mappingexportAction()
    {

        if ($feed_id = $this->getRequest()->getParam('id')) {

            $feed = Mage::getModel('gomage_feed/item')->load($feed_id);

            $this->getResponse()->setBody($feed->getContent());
            $this->getResponse()->setHeader('Content-Type', 'text');
            $this->getResponse()->setHeader('Content-Disposition', 'attachment; filename="mapping-export-' . basename($feed->getFilename()) . '.txt";');

        }

    }

    public function mappingexportftpAction()
    {

        if (($post = $this->getRequest()->getPost()) && ($id = $this->getRequest()->getParam('id'))) {

            try {

                $system  = basename($this->getRequest()->getParam('feed_system'));
                $section = basename($this->getRequest()->getParam('feed_section'));

                $fileDir = Mage::getBaseDir('media') . DS . 'productsfeed' . DS . 'examples' . DS . $system;
                if (!file_exists($fileDir)) {
                    mkdir($fileDir);
                    chmod($fileDir, 0777);
                }

                $feed = Mage::getModel('gomage_feed/item')->load($id);

                $fp = fopen($fileDir . DS . $section, 'a');
                fwrite($fp, $feed->getContent());
                fclose($fp);

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('core')->__('Data successfully exported'));

            } catch (Exception $e) {

                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('core')->__('Unknown error'));

            }

            return $this->_redirect('*/*/edit', array('id' => $id, 'tab' => 'content_section'));
        }

        $this->_initAction();
        if ($id = $this->getRequest()->getParam('id')) {
            Mage::register('gomage_feed', Mage::getModel('gomage_feed/item')->load($id));
            $this->_addContent($this->getLayout()->createBlock('gomage_feed/adminhtml_items_mappingexportftp'))->_addLeft($this->getLayout()->createBlock('gomage_feed/adminhtml_items_mappingexportftp_tabs'));

        }
        $this->renderLayout();
    }

    protected function prepareOutputType($output_type)
    {
        $output_type = (array)$output_type;
        if (count($output_type)) {
            $remove = array();
            if (in_array('htmlspecialchars', $output_type) && in_array('htmlspecialchars_decode', $output_type)) {
                $remove[] = 'htmlspecialchars_decode';
            }
            if (in_array('int', $output_type) && in_array('float', $output_type)) {
                $remove[] = 'int';
            }
            $output_type = array_diff($output_type, $remove);
        }
        return implode(',', $output_type);
    }

    public function saveAction()
    {

        if ($data = $this->getRequest()->getPost()) {

            try {
                $id = $this->getRequest()->getParam('id');

                $model = Mage::getModel('gomage_feed/item');

                if (isset($data['field'])) {
                    $content_data        = array();
                    $content_data_sorted = array();

                    foreach ($data['field'] as $field) {
                        $field['output_type'] = $this->prepareOutputType($field['output_type']);
                        if (intval($field['order']) && !isset($content_data_sorted[$field['order']])) {
                            $content_data_sorted[intval($field['order'])] = $field;
                        } else {
                            $field['order'] = 0;
                            $content_data[] = $field;
                        }
                    }

                    ksort($content_data_sorted);
                    $data['content'] = json_encode(array_merge($content_data, $content_data_sorted));
                }

                if (isset($data['filter']) && is_array($data['filter'])) {
                    $data['filter'] = json_encode(array_merge($data['filter'], array()));
                } else {
                    $data['filter'] = json_encode(array());
                }

                if (isset($data['upload_day']) && is_array($data['upload_day'])) {
                    $data['upload_day'] = implode(',', $data['upload_day']);
                }
                if (isset($data['upload_interval']) && in_array($data['upload_interval'], array(12, 24))) {
                    $data['upload_hour_to'] = null;
                }

                if (isset($data['generate_day']) && is_array($data['generate_day'])) {
                    $data['generate_day'] = implode(',', $data['generate_day']);
                }
                if (isset($data['generate_interval']) && in_array($data['generate_interval'], array(12, 24))) {
                    $data['generate_hour_to'] = null;
                }

                $model->setData($data)->setId($id)->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('core')->__('Data successfully saved'));

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }

            } catch (Mage_Core_Exception $e) {

                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());

                Mage::getSingleton('core/session')->setFeedData($data);

                if ($model->getId() > 0) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                } else {
                    $this->_redirect('*/*/new', array('type' => $model->getType()));
                }
                return false;

            } catch (Exception $e) {

                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('core')->__('Can’t save data'));

                Mage::getSingleton('core/session')->setFeedData($data);

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

    public function massGenerateAction()
    {

        $server_errors = Mage::helper('gomage_feed/generator')->checkServerParams();
        if (count($server_errors)) {
            foreach ($server_errors as $error) {
                Mage::getSingleton('adminhtml/session')->addError($error);
            }
            return $this->_redirect('*/*/');
        }

        if ($ids = $this->getRequest()->getParam('id')) {
            if (is_array($ids) && !empty($ids)) {
                foreach ($ids as $id) {
                    try {
                        $feed = Mage::getModel('gomage_feed/item')->load($id);
                        $feed->generate();
                        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('core')->__('File "%s" is created', $feed->getFileNameWithExt()));
                    } catch (Exception $e) {
                        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('core')->__('Can\'t generate feed file'));
                        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('core')->__('Change "Number of Products" in the Advanced Settings.
                                    Try to change "Number of Products" in the Advanced Settings.
                                    For example: set "Number of Products" equal 50 or 100.'
                        )
                        );
                        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('core')->__('If "Time out" error.
        							Please ask your server administrator to increase script run times. Learn more at <a target="_blank" href="http://php.net/manual/en/function.set-time-limit.php">http://php.net/manual/en/function.set-time-limit.php</a>'
                        )
                        );
                    }
                }
            }
        }
        $this->_redirect('*/*/');
    }

    public function massUploadAction()
    {
        if ($ids = $this->getRequest()->getParam('id')) {
            if (is_array($ids) && !empty($ids)) {
                foreach ($ids as $id) {
                    $item = Mage::getModel('gomage_feed/item')->load($id);

                    try {

                        if ($item->getFtpActive() > 0) {

                            if ($item->ftpUpload()) {

                                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('core')->__('%s - File "%s" was uploaded!', $item->getName(), $item->getFileNameWithExt()));

                            }

                        } else {

                            Mage::getSingleton('adminhtml/session')->addNotice(Mage::helper('core')->__('%s - FTP disabled', $item->getName()));

                        }

                    } catch (Mage_Core_Exception $e) {

                        Mage::getSingleton('adminhtml/session')->addError($item->getName() . ' - ' . $e->getMessage());

                    } catch (Exception $e) {

                        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('core')->__('%s - Can\'t upload. Please, check your FTP Settings or Hosting Settings', $item->getFileNameWithExt()));

                    }
                }
            }

        }

        $this->_redirect('*/*/');

    }

    protected function _deleteItems($ids)
    {
        if (is_array($ids) && !empty($ids)) {
            foreach ($ids as $id) {
                $item = Mage::getModel('gomage_feed/item')->load($id);
                $item->delete();
            }
        }
    }

    public function newAction()
    {
        $this->_initAction();

        if ($data = Mage::getSingleton('core/session')->getFeedData()) {
            Mage::register('gomage_feed', Mage::getModel('gomage_feed/item')->addData($data));
            Mage::getSingleton('core/session')->setFeedData(null);
        }

        $this->_addContent($this->getLayout()->createBlock('gomage_feed/adminhtml_items_edit'))->_addLeft($this->getLayout()->createBlock('gomage_feed/adminhtml_items_edit_tabs'));

        $this->renderLayout();

    }

    public function editAction()
    {
        $server_errors = Mage::helper('gomage_feed/generator')->checkServerParams();
        foreach ($server_errors as $error) {
            Mage::getSingleton('adminhtml/session')->addError($error);
        }

        $this->_initAction();

        if ($id = $this->getRequest()->getParam('id')) {
            Mage::register('gomage_feed', Mage::getModel('gomage_feed/item')->load($id));
        }

        $this->_addContent($this->getLayout()->createBlock('gomage_feed/adminhtml_items_edit'))->_addLeft($this->getLayout()->createBlock('gomage_feed/adminhtml_items_edit_tabs'));

        $this->renderLayout();
    }

    public function uploadAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $item = Mage::getModel('gomage_feed/item')->load($id);
            try {
                if ($item->ftpUpload()) {
                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('core')->__('File "%s" was uploaded!', $item->getFileNameWithExt()));
                }

            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($item->getFileNameWithExt() . ' - ' . $e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('core')->__('%s - Can\'t upload. Please, check your FTP Settings or Hosting Settings', $item->getFileNameWithExt()));
            }
            return $this->_redirect('*/*/edit', array('id' => $id));
        }

        $this->_redirect('*/*/index');
    }

    public function getattributevaluefieldAction()
    {
        if ($code = $this->getRequest()->getParam('attribute_code')) {

            $name     = $this->getRequest()->getParam('element_name');
            $store_id = $this->getRequest()->getParam('store_id');
            $iterator = $this->getRequest()->getParam('iterator');

            if ($code == 'product_type') {
                $condition = GoMage_Feed_Block_Adminhtml_Items_Edit_Tab_Filter::getConditionSelectLight($iterator);
            } else {
                $condition = GoMage_Feed_Block_Adminhtml_Items_Edit_Tab_Filter::getConditionSelect($iterator);
            }

            $this->getResponse()->setBody(Zend_Json::encode(array('attributevalue' => GoMage_Feed_Block_Adminhtml_Items_Edit_Tab_Filter::getAttributeValueField($code, $name, null, $store_id), 'condition' => $condition, 'iterator' => $iterator)));
        }

    }

    public function processInfoAction()
    {
        $result = array();

        $feed_id      = $this->getRequest()->getParam('feed_id');
        $stop_command = $this->getRequest()->getParam('stop_command', false);

        $generate_info = Mage::helper('gomage_feed/generator')->getGenerateInfo($feed_id);

        if ($stop_command) {
            $generate_info->stop()->save();
        }

        $result['stop'] = $generate_info->getData('stopped');

        $errors = $generate_info->getData('errors');
        if (count($errors)) {
            $result['error'] = implode(' ', $errors);
        }

        $percent = 0;
        if (($generated_records = $generate_info->getData('generated_records')) &&
            ($total_records = $generate_info->getData('total_records'))
        ) {
            $percent = round($generated_records * 100 / $total_records);
        }
        $result['percent'] = $percent;

        list($hour, $min, $sec) = $generate_info->getGenerationTime();

        $result['time'] = Mage::helper('gomage_feed/generator')->formatGenerationTime($hour, $min, $sec);


        if ($generate_info->getData('finished')) {
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('core')->__('File was generated.'));
            $result['redirect'] = $this->getUrl('*/*/edit', array('id' => $feed_id));
        }

        $this->getResponse()->setBody(Zend_Json::encode($result));
    }

}