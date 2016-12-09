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
 * @version      Release: 3.7.0
 * @since        Class available since Release 1.0
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
        $this->_writerFactory  = Mage::getSingleton('gomage_feed/writer_factory');
        $this->_contentFactory = Mage::getSingleton('gomage_feed/content_factory');
        $this->_dateTime       = Mage::getModel('core/date');
    }

    /**
     * @param  int $feedId
     * @throws \Exception
     */
    public function generate($feedId)
    {
        try {
            $this->_feed = Mage::getModel('gomage_feed/item')->load($feedId);
            $this->_start();

            $page  = 1;
            $limit = $this->_feed->getLimit();

            while ($items = $this->_getReader()->read($page, $limit)) {
                $this->log(Mage::helper('gomage_feed')->__('Page - %1', $page));
                foreach ($items as $item) {
                    $data = $this->_getRows()->calc($item);
                    $this->_getWriter()->write($data);
                }
                $page++;
            }

            $this->_finish();
        } catch (Exception $e) {
            $this->log($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    protected function _start()
    {
        $this->_time = microtime(true);
        $this->log(Mage::helper('gomage_feed')->__('Start generation'));
        Mage::helper('gomage_feed/generator')->setServerConfig($this->_feed);
    }

    protected function _finish()
    {
        $this->_time = microtime(true) - $this->_time;
        $this->_time = max(array($this->_time, 1));
        $this->_feed->setData('generation_time', $this->_dateTime->gmtDate('H:i:s', $this->_time))
            ->setData('generated_at', $this->_dateTime->gmtDate('Y-m-j H:i:s'))
            ->save();
        $this->log(Mage::helper('gomage_feed')->__('Finish'));
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
            $this->_reader = Mage::getModel('gomage_feed/reader_collection', $params);
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

            if ($this->_feed->getType() == 'csv') {
                $arguments = array_merge($arguments,
                    array(
                        'delimiter'      => Mage::getSingleton('gomage_feed/adminhtml_system_config_source_csv_delimiter')->getSymbol($this->_feed->getDelimiter()),
                        'enclosure'      => Mage::getSingleton('gomage_feed/adminhtml_system_config_source_csv_enclosure')->getSymbol($this->_feed->getEnclosure()),
                        'isHeader'       => boolval($this->_feed->getShowHeaders()),
                        'additionHeader' => $this->_feed->getUseAdditionHeader() ? $this->_feed->getAdditionHeader() : ''
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
     * @param $message
     * @param array $context
     */
    protected function log($message, array $context = array())
    {
        //TODO
        /*
        if ($this->_feed && !$this->_logHandler) {
            $this->_logHandler = $this->_objectManager->create('GoMage\Feed\Model\Logger\Handler', [
                    'fileName' => '/var/log/feed-' . $this->_feed->getId() . '.log'
                ]
            );
            $this->_logger->setHandlers([$this->_logHandler]);
        }

        $this->_logger->info($message, $context);
        */
    }

}