<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2020 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.3.2
 * @since        Class available since Release 3.6
 */
class GoMage_Feed_Adminhtml_Gomage_Feed_AmazonController extends Mage_Adminhtml_Controller_Action
{

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/config/gomage_feedpro');
    }

    public function generatecodeAction()
    {
        $session = Mage::getSingleton('adminhtml/session');
        $error   = false;
        $code_id = $this->getRequest()->getParam('code_id');
        $amazon  = Mage::getModel('gomage_feed/amazon');
        $data    = Mage::getStoreConfig('gomage_feedpro/amazon/config');
        $array   = array();
        if ($data) {
            $array = unserialize($data);
        }

        foreach ($array as $key => $val) {
            if ($key == $code_id) {
                if ($val['type'] == 1) {
                    $type = $val['input_code'];
                    $name = $val['value'];
                }
                if ($val['type'] == 0) {
                    if ($val['value'] == '') {
                        $error = true;
                        $session->addError(Mage::helper('gomage_feed')->__('Value empty!'));
                    } else {
                        $name = str_replace(' ', '_', $val['value']);
                        $type = $val['input_code'];
                        $amazon->createAttribute($val['value'], $name);
                        $array[$key]['type'] = 1;
                        $amazon->saveConfig($array);
                    }
                }

                if ($error == false) {
                    if ($type && $name) {
                        $amazon->codeGenerationItem($name, $type, $val['action']);
                        $session->addSuccess(Mage::helper('gomage_feed')->__('Codes %s have been generated.', strtoupper($type)));
                    } else {
                        $session->addError(Mage::helper('gomage_feed')->__('Type or value of the attribute is not defined.'));
                    }
                }
            }
        }
        return $this->getResponse()->setBody(Zend_Json::encode($error));
    }

}