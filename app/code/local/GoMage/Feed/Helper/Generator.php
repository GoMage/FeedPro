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
 * @since        Class available since Release 3.0
 */
class GoMage_Feed_Helper_Generator extends Mage_Core_Helper_Abstract
{

    /** @var  Zend_Http_Client */
    protected $http_client;

    /**
     * @var
     */
    protected $customFeedDirectory;

    /**
     * GoMage_Feed_Helper_Generator constructor.
     */
    public function __construct()
    {
        $this->customFeedDirectory = Mage::helper('gomage_feed')->getCustomFeedDirectory();
    }

    public function checkServerParams()
    {
        $errors   = array();
        $base_dir = $this->getBaseDir();
        $log_dir  = $this->getLogDir();

        try {
            if (!file_exists($base_dir)) {
                mkdir($base_dir);
            }
            chmod($base_dir, 0777);
            if (!file_exists($log_dir)) {
                mkdir($log_dir);
            }
            chmod($log_dir, 0777);
        } catch (Exception $e) {
            $errors[] = Mage::helper('gomage_feed')->__('Check the Permission for the "' . $this->customFeedDirectory . '" directory.
							Check that the "' . $this->customFeedDirectory . '" directory of your Magento has permission equal to 750 or 0750.'
            );
        }

        return $errors;
    }

    public function getBaseDir()
    {
        $base_dir = Mage::getBaseDir('base') . DS . $this->customFeedDirectory . DS . GoMage_Feed_Model_Writer_WriterInterface::DIRECTORY;
        try {
            if (!file_exists($base_dir)) {
                mkdir($base_dir, 0777, true);
            }
            chmod($base_dir, 0777);
        } catch (Exception $e) {
            Mage::logException($e);
        }
        return $base_dir;
    }

    public function getLogDir()
    {
        $log_dir = Mage::getBaseDir('base') . DS .$this->customFeedDirectory . DS . GoMage_Feed_Model_Writer_WriterInterface::DIRECTORY . DS . 'logs';
        try {
            if (!file_exists($log_dir)) {
                mkdir($log_dir, 0777, true);
            }
            chmod($log_dir, 0777);
        } catch (Exception $e) {
            Mage::logException($e);
        }
        return $log_dir;
    }

    public function setServerConfig($feed)
    {
        $store = $feed->getStore();
        if (Mage::getStoreConfig('gomage_feedpro/configuration/enable', $store)) {
            if (Mage::getStoreConfig('gomage_feedpro/configuration/memory_limit', $store)) {
                ini_set("memory_limit", Mage::getStoreConfig('gomage_feedpro/configuration/memory_limit', $store) . "M");
            }
            if (Mage::getStoreConfig('gomage_feedpro/configuration/upload_max_filesize')) {
                ini_set("upload_max_filesize", Mage::getStoreConfig('gomage_feedpro/configuration/upload_max_filesize', $store) . "M");
            }
            if (Mage::getStoreConfig('gomage_feedpro/configuration/post_max_size', $store)) {
                ini_set("post_max_size", Mage::getStoreConfig('gomage_feedpro/configuration/post_max_size', $store) . "M");
            }
            if (Mage::getStoreConfig('gomage_feedpro/configuration/time_limit', $store) != '') {
                set_time_limit(intval(Mage::getStoreConfig('gomage_feedpro/configuration/time_limit', $store)));
            }
        }
    }

    public function getGenerateInfo($feed_id, $init = false)
    {
        $file_path = $this->getLogDir() . DS . 'feed-' . $feed_id . '.info';

        clearstatcache();
        if (file_exists($file_path) && !$init) {
            $info = file_get_contents($file_path);
            $info = unserialize($info);
            if ($info instanceof GoMage_Feed_Model_Generator_Info) {
                return $info;
            }
        }
        $info = Mage::getModel('gomage_feed/generator_info');
        $info->setData('file_path', $file_path)->save();
        return $info;
    }

    /**
     * @return Zend_Http_Client
     */
    public function getHttpClient()
    {
        if (!$this->http_client) {
            $this->http_client = new Zend_Http_Client();
            $this->http_client->setConfig(array('timeout' => 60));
        }
        return $this->http_client;
    }

    /**
     * @param $hour
     * @param $min
     * @param $sec
     * @param bool $full
     * @return string
     */
    public function formatGenerationTime($hour, $min, $sec, $full = false)
    {
        if ($full) {
            return $this->__('%sh %smin %ssec', $hour, $min, $sec);
        } else {
            if ($hour) {
                return $this->__('%sh %smin %ssec', $hour, $min, $sec);
            } else {
                return $this->__('%smin %ssec', $min, $sec);
            }
        }
    }

    /**
     * @param $interval
     * @param $hour_from
     * @param $hour_to
     * @param $last_run
     * @return bool
     */
    public function needRunCron($interval, $hour_from, $hour_to, $last_run)
    {
        $current_time = date('G');
        $interval     = intval($interval);
        $hour_from    = intval($hour_from);
        $hour_to      = intval($hour_to);
        $last_run     = strtotime($last_run);

        switch ($interval) {
            case 12:
            case 24:
                if ($hour_from != $current_time) {
                    return false;
                }
                if (($last_run + $interval * 60 * 60) > time()) {
                    return false;
                }
                break;
            default:
                if (!$hour_to) {
                    $hour_to = 24;
                }

                $hours = array();
                if ($hour_from > $hour_to) {
                    for ($i = $hour_from; $i <= 23; $i++) {
                        $hours[] = $i;
                    }
                    for ($i = 0; $i <= $hour_to; $i++) {
                        $hours[] = $i;
                    }
                } else {
                    for ($i = $hour_from; $i <= $hour_to; $i++) {
                        if ($i == 24) {
                            $hours[] = 0;
                        } else {
                            $hours[] = $i;
                        }
                    }
                }
                if (!in_array($current_time, $hours)) {
                    return false;
                }

                if (($last_run + $interval * 60 * 60) > time()) {
                    return false;
                }
        }

        return true;
    }

}
