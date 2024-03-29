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
 * @since        Class available since Release 1.0
 */
class GoMage_Feed_Block_Adminhtml_Items_Edit_Tab_Content_Csv extends Mage_Adminhtml_Block_Template
{
    protected $_config;

    public function __construct()
    {
        parent::__construct();
        $this->getConfig()->setUrl(Mage::getModel('adminhtml/url')->addSessionParam()->getUrl('*/*/ajaxupload'));
        $this->getConfig()->setParams(array('form_key' => $this->getFormKey()));
        $this->getConfig()->setFileField('file');
        $this->getConfig()->setFilters(array(
                'all' => array(
                    'label' => Mage::helper('adminhtml')->__('All Files'),
                    'files' => array('*.*')
                )
            )
        );
    }

    public function getHtmlId()
    {
        if ($this->getData('upload_id') === null) {
            $this->setData('upload_id', 'id_gomage_feed_upload');
        }
        return $this->getData('upload_id');
    }

    public function getConfigJson()
    {
        if (Mage::helper('gomage_feed')->isModuleExists('Mage_Uploader')) {

            $uploaderConfig = Mage::getModel('uploader/config_uploader');
            $uploaderConfig->setFileParameterName('file')
                ->setTarget(
                    Mage::getModel('adminhtml/url')
                        ->addSessionParam()
                        ->getUrl('*/*/ajaxupload', array('_secure' => true))
                );
            $miscConfig = Mage::getModel('uploader/config_misc');
            $miscConfig->setReplaceBrowseWithRemove(true);
            $browseButtonConfig = Mage::getModel('uploader/config_browsebutton');

            $config = array(
                'uploaderConfig' => $uploaderConfig->getData(),
                'elementIds'     => array(
                    'container'    => $this->getHtmlId() . '-container',
                    'browse'       => array($this->getHtmlId() . '-browse'),
                    'upload'       => array($this->getHtmlId() . '-upload'),
                    'templateFile' => $this->getHtmlId() . '-template'
                ),
                'browseConfig'   => $browseButtonConfig->getData(),
                'miscConfig'     => $miscConfig->getData(),
            );
        } else {
            $config = $this->getConfig()->getData();
        }
        return Zend_Json::encode($config);
    }

    public function getConfig()
    {
        if (is_null($this->_config)) {
            $this->_config = new Varien_Object();
        }

        return $this->_config;
    }

    public function getJsObjectName()
    {
        return $this->getHtmlId() . 'JsObject';
    }

    public function getPostMaxSize()
    {
        return ini_get('post_max_size');
    }

    public function getUploadMaxSize()
    {
        return ini_get('upload_max_filesize');
    }

    public function getDataMaxSize()
    {
        return min($this->getPostMaxSize(), $this->getUploadMaxSize());
    }

    public function getDataMaxSizeInBytes()
    {
        $iniSize = $this->getDataMaxSize();
        $size    = substr($iniSize, 0, strlen($iniSize) - 1);
        switch (strtolower(substr($iniSize, strlen($iniSize) - 1))) {
            case 't':
                $parsedSize = $size * (1024 * 1024 * 1024 * 1024);
                break;
            case 'g':
                $parsedSize = $size * (1024 * 1024 * 1024);
                break;
            case 'm':
                $parsedSize = $size * (1024 * 1024);
                break;
            case 'k':
                $parsedSize = $size * 1024;
                break;
            case 'b':
            default:
                $parsedSize = $size;
                break;
        }
        return $parsedSize;
    }

    public function getUploaderUrl($url)
    {
        if (!is_string($url)) {
            $url = '';
        }
        $design = Mage::getDesign();
        $theme  = $design->getTheme('skin');
        if (empty($url) || !$design->validateFile($url, array('_type' => 'skin', '_theme' => $theme))) {
            $theme = $design->getDefaultTheme();
        }
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) .
            $design->getArea() . '/' . $design->getPackageName() . '/' . $theme . '/' . $url;
    }

    public function getDeleteButtonHtml()
    {
        return $this->getChildHtml('delete_button');
    }

    protected function _prepareLayout()
    {
        $this->setChild(
            'delete_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->addData(array(
                        'id'      => '{{id}}-delete',
                        'class'   => 'delete',
                        'type'    => 'button',
                        'label'   => Mage::helper('adminhtml')->__('Remove'),
                        'onclick' => $this->getJsObjectName() . '.removeFile(\'{{fileId}}\')'
                    )
                )
        );
        return parent::_prepareLayout();
    }

    public function getFeed()
    {
        if (Mage::registry('gomage_feed')) {
            return Mage::registry('gomage_feed');
        } else {
            return new Varien_Object();
        }
    }

}