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
class GoMage_Feed_Model_Generator
{

    /**
     * @var GoMage_Feed_Model_Item
     */
    protected $_feed;

    /**
     * @var GoMage_Feed_Model_Feed_Row_Collection
     */
    protected $_rows;

    /**
     * @var GoMage_Feed_Model_Reader_ReaderInterface
     */
    protected $_reader;

    /**
     * @var GoMage_Feed_Model_Reader_Factory
     */
    protected $_readerFactory;

    /**
     * @var GoMage_Feed_Model_Writer_WriterInterface
     */
    protected $_writer;

    /**
     * @var GoMage_Feed_Model_Writer_Factory
     */
    protected $_writerFactory;

    /**
     * @var float
     */
    protected $_time;

    /**
     * @var Mage_Core_Model_Date
     */
    protected $_dateTime;

    /**
     * @var GoMage_Feed_Model_Content_Factory
     */
    protected $_contentFactory;

    /**
     * @var GoMage_Feed_Model_Content_ContentInterface
     */
    protected $_content;


    public function __construct()
    {
        $this->_readerFactory  = Mage::getSingleton('gomage_feed/reader_factory');
        $this->_writerFactory  = Mage::getSingleton('gomage_feed/writer_factory');
        $this->_contentFactory = Mage::getSingleton('gomage_feed/content_factory');
        $this->_dateTime       = Mage::getModel('core/date');
    }

    /**
     * @param  int $feedId
     * @throws Mage_Core_Exception
     */
    public function generate($feedId)
    {
        try {
            $this->_feed = Mage::getModel('gomage_feed/item')->load($feedId);
            $this->_start();

            $total_records = $this->_getReader()->read()->count();
            $generate_info = Mage::helper('gomage_feed/generator')->getGenerateInfo($this->_feed->getId());
            $generate_info->setData('total_records', $total_records)->save();

            $page  = 1;
            $limit = $this->_feed->getIterationLimit();
            if (!$limit) {
                $limit = round($total_records / 100);
            }

            while ($items = $this->_getReader()->read($page, $limit)) {
                $this->log(Mage::helper('gomage_feed')->__('Page - %s', $page));
                foreach ($items as $item) {
                    if ($this->_getReader()->isValidItem($item)) {
                        $item->setStoreId($this->_feed->getStoreId());
                        $data = $this->_getRows()->calc($item);
                        $this->_getWriter()->write($data);
                    }
                }
                $generate_info = Mage::helper('gomage_feed/generator')->getGenerateInfo($this->_feed->getId());
                if ($generate_info->getData('stopped')) {
                    Mage::throwException(Mage::helper('gomage_feed')->__('Stopped generation.'));
                }
                if ($limit) {
                    $generate_info->addGeneratedRecords($limit)->save();
                }
                $page++;
            }

            $this->_finish();
        } catch (Exception $e) {
            $this->log($e->getMessage());
            Mage::throwException($e->getMessage());
        }
    }

    protected function _start()
    {
        $this->_time = microtime(true);
        $this->log(Mage::helper('gomage_feed')->__('Start generation'));
        Mage::helper('gomage_feed/generator')->setServerConfig($this->_feed);
        $generate_info = Mage::helper('gomage_feed/generator')->getGenerateInfo($this->_feed->getId(), true);
        $generate_info->start()->save();
    }

    protected function _finish()
    {
        $this->_time = microtime(true) - $this->_time;
        $this->_time = max(array($this->_time, 1));
        $this->_feed->setData('generation_time', date('H:i:s', $this->_time))
            ->setData('generated_at', $this->_dateTime->gmtDate('Y-m-j H:i:s'))
            ->save();
        $this->log(Mage::helper('gomage_feed')->__('Finish'));
        $generate_info = Mage::helper('gomage_feed/generator')->getGenerateInfo($this->_feed->getId());
        $generate_info->finish()->save();
    }

    /**
     * @return GoMage_Feed_Model_Reader_ReaderInterface
     */
    protected function _getReader()
    {
        if (is_null($this->_reader)) {
            /** @var GoMage_Feed_Model_Reader_Params $params */
            $params        = Mage::getModel('gomage_feed/reader_params', array(
                    'attributes'  => $this->_getRows()->getAttributes(),
                    'conditions'  => $this->_feed->getConditions(),
                    'store_id'    => $this->_feed->getStoreId(),
                    'visibility'  => $this->_feed->getVisibility(),
                    'use_layer'   => $this->_feed->getUseLayer(),
                    'is_disabled' => $this->_feed->getUseDisabled(),
                )
            );
            $this->_reader = $this->_readerFactory->create($this->_getContent()->getEntityType(), $params);
        }
        return $this->_reader;
    }

    /**
     * @return GoMage_Feed_Model_Writer_WriterInterface
     */
    protected function _getWriter()
    {
        if (is_null($this->_writer)) {
            $arguments = array(
                'fileName' => $this->_feed->getFileNameWithExt(),
            );

            if ($this->_feed->getType() == GoMage_Feed_Model_Adminhtml_System_Config_Source_Feed_Type::CSV_TYPE) {
                $arguments = array_merge($arguments,
                    array(
                        'delimiter'      => Mage::getSingleton('gomage_feed/adminhtml_system_config_source_csv_delimiter')->getSymbol($this->_feed->getDelimiter()),
                        'enclosure'      => Mage::getSingleton('gomage_feed/adminhtml_system_config_source_csv_enclosure')->getSymbol($this->_feed->getEnclosure()),
                        'isHeader'       => boolval($this->_feed->getShowHeaders()),
                        'additionHeader' => $this->_feed->getUseAdditionHeader() ? $this->_feed->getAdditionHeader() : '',
                        'removeLb'       => boolval($this->_feed->getRemoveLb()),
                    )
                );
            } else {
                $arguments['content'] = $this->_getContent();
            }

            $this->_writer = $this->_writerFactory->create($this->_feed->getType(), $arguments);
        }
        return $this->_writer;
    }

    /**
     * @return GoMage_Feed_Model_Feed_Row_Collection
     */
    protected function _getRows()
    {
        if (is_null($this->_rows)) {
            $this->_rows = $this->_getContent()->getRows();
        }
        return $this->_rows;
    }

    /**
     * @return GoMage_Feed_Model_Content_ContentInterface
     */
    protected function _getContent()
    {
        if (is_null($this->_content)) {
            $this->_content = $this->_contentFactory->create($this->_feed->getType(),
                array(
                    'content' => $this->_feed->getContent()
                )
            );
        }
        return $this->_content;
    }

    /**
     * @param string $text
     * @param bool $rewrite
     */
    protected function log($text = '', $rewrite = false)
    {
        $text .= "\n";
        $log_file = Mage::helper('gomage_feed/generator')->getLogDir() . DS . 'log-' . $this->_feed->getId() . '.txt';
        if ($rewrite || !file_exists($log_file)) {
            @file_put_contents($log_file, $text);
        } else {
            $fp = fopen($log_file, 'a');
            fwrite($fp, $text);
            fclose($fp);
        }
    }

}