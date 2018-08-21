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
 * @version      Release: 4.2.0
 * @since        Class available since Release 1.0
 */
class GoMage_Feed_IndexController extends Mage_Core_Controller_Front_Action
{

    public function generateAction()
    {
        $feed = Mage::getModel('gomage_feed/item')->load($this->getRequest()->getParam('feed_id'));
        ignore_user_abort(true);
        $this->getResponse()
            ->setHttpResponseCode(200)
            ->setHeader('Connection', 'close', true)
            ->setHeader('Content-Length', '0', true);
        $this->getResponse()->clearBody();
        $this->getResponse()->sendResponse();
        $generate_info = Mage::helper('gomage_feed/generator')->getGenerateInfo($feed->getId());
        if (!$generate_info->inProcess()) {
            try {
                /** @var GoMage_Feed_Model_Generator $generator */
                $generator = Mage::getModel('gomage_feed/generator');
                $generator->generate($feed->getId());
            } catch (Exception $e) {
                $generate_info = Mage::helper('gomage_feed/generator')->getGenerateInfo($feed->getId());
                if (Mage::helper('catalog/product_flat')->isEnabled()) {
                    $generate_info->setError('Please follow a few steps https://wiki.gomage.com/hc/en-us/articles/360011593992-Feed-generation-fails-with-Use-Flat-Catalog-Product-Yes- in order to be able to generate the feed file successfully when Use Flat Catalog Product option is enabled')->save();
                } else {
                    $generate_info->setError($e->getMessage())->save();
                }

            }
        }
    }

}