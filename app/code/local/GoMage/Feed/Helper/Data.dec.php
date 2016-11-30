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
class GoMage_Feed_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getConfigData($node)
    {
        return Mage::getStoreConfig('gomage_feed/' . $node);
    }

    public function isModuleExists($moduleName)
    {
        $modules = (array)Mage::getConfig()->getNode('modules')->children();
        return isset($modules[$moduleName]);
    }

    public function getAllStoreDomains()
    {
        $domains = array();

        foreach (Mage::app()->getWebsites() as $website) {

            $url = $website->getConfig('web/unsecure/base_url');

            if ($domain = trim(preg_replace('/^.*?\\/\\/(.*)?\\//', '$1', $url))) {
                $domains[] = $domain;
            }

            $url = $website->getConfig('web/secure/base_url');

            if ($domain = trim(preg_replace('/^.*?\\/\\/(.*)?\\//', '$1', $url))) {
                $domains[] = $domain;
            }
        }
        return array_unique($domains);
    }

    public function getAvailabelWebsites()
    {
        return $this->_w();
    }

    public function getAvailavelWebsites()
    {
        return $this->_w();
    }

    protected function _w()
    {

        if (!Mage::getStoreConfig('gomage_activation/feed/installed') ||
            (intval(Mage::getStoreConfig('gomage_activation/feed/count')) > 10)
        ) {
            return array();
        }

        $time_to_update = 60 * 60 * 24 * 15;

        $r = Mage::getStoreConfig('gomage_activation/feed/ar');
        $t = Mage::getStoreConfig('gomage_activation/feed/time');
        $s = Mage::getStoreConfig('gomage_activation/feed/websites');

        $last_check = str_replace($r, '', Mage::helper('core')->decrypt($t));

        $allsites = explode(',', str_replace($r, '', Mage::helper('core')->decrypt($s)));
        $allsites = array_diff($allsites, array(""));

        if (($last_check + $time_to_update) < time()) {
            $this->a(Mage::getStoreConfig('gomage_activation/feed/key'),
                intval(Mage::getStoreConfig('gomage_activation/feed/count')),
                implode(',', $allsites)
            );
        }

        return $allsites;
    }

    public function a($k, $c = 0, $s = '')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, sprintf('https://www.gomage.com/index.php/gomage_downloadable/key/check'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'key=' . urlencode($k) . '&sku=feed-pro&domains=' . urlencode(implode(',', $this->getAllStoreDomains())) . '&ver=' . urlencode('3.7.0'));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        $content = curl_exec($ch);

        try {
            $r = Zend_Json::decode($content);
        } catch (\Exception $e) {
            $r = array();
        }

        $e = Mage::helper('core');
        if (empty($r)) {

            $value1 = Mage::getStoreConfig('gomage_activation/feed/ar');

            $groups = array(
                'feed' => array(
                    'fields' => array(
                        'ar'       => array(
                            'value' => $value1
                        ),
                        'websites' => array(
                            'value' => (string)Mage::getStoreConfig('gomage_activation/feed/websites')
                        ),
                        'time'     => array(
                            'value' => (string)$e->encrypt($value1 . (time() - (60 * 60 * 24 * 15 - 1800)) . $value1)
                        ),
                        'count'    => array(
                            'value' => $c + 1)
                    )
                )
            );

            Mage::getModel('adminhtml/config_data')
                ->setSection('gomage_activation')
                ->setGroups($groups)
                ->save();

            Mage::getConfig()->reinit();
            Mage::app()->reinitStores();

            return;
        }

        $value1 = '';
        $value2 = '';


        if (isset($r['d']) && isset($r['c'])) {
            $value1 = $e->encrypt(base64_encode(Zend_Json::encode($r)));


            if (!$s) {
                $s = Mage::getStoreConfig('gomage_activation/feed/websites');
            }

            $s = array_slice(explode(',', $s), 0, $r['c']);

            $value2 = $e->encrypt($value1 . implode(',', $s) . $value1);

        }
        $groups = array(
            'feed' => array(
                'fields' => array(
                    'ar'        => array(
                        'value' => $value1
                    ),
                    'websites'  => array(
                        'value' => (string)$value2
                    ),
                    'time'      => array(
                        'value' => (string)$e->encrypt($value1 . time() . $value1)
                    ),
                    'installed' => array(
                        'value' => 1
                    ),
                    'count'     => array(
                        'value' => 0)

                )
            )
        );

        Mage::getModel('adminhtml/config_data')
            ->setSection('gomage_activation')
            ->setGroups($groups)
            ->save();

        Mage::getConfig()->reinit();
        Mage::app()->reinitStores();

    }

    public function ga()
    {
        $ar = base64_decode(Mage::helper('core')->decrypt(Mage::getStoreConfig('gomage_activation/feed/ar')));
        return $ar ? Zend_Json::decode($ar) : array();
    }

    public function getSystemSections()
    {
        $data    = array();
        $fileDir = Mage::getBaseDir('media') . DS . 'productsfeed' . DS . 'examples';
        if (is_dir($fileDir) && $handle = opendir($fileDir)) {
            while (false !== ($dir = readdir($handle))) {
                if ($dir != '.' && $dir != '..') {
                    if (is_dir($fileDir . DS . $dir) && ($sub_handle = opendir($fileDir . DS . $dir))) {
                        $data[$dir] = array();
                        while (false !== ($file = readdir($sub_handle))) {
                            if ($file != '.' && $file != '..') {
                                $data[$dir][] = $file;
                            }
                        }
                        closedir($sub_handle);
                    }
                }
            }
            closedir($handle);
        }
        return $data;
    }

    public function getIsAnymoreVersion($major, $minor, $revision = 0)
    {
        $version_info = Mage::getVersion();
        $version_info = explode('.', $version_info);

        if ($version_info[0] > $major) {
            return true;
        } elseif ($version_info[0] == $major) {
            if ($version_info[1] > $minor) {
                return true;
            } elseif ($version_info[1] == $minor) {
                if ($version_info[2] >= $revision) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function notify()
    {
        $frequency = intval(Mage::app()->loadCache('gomage_notifications_frequency'));
        if (!$frequency) {
            $frequency = 24;
        }
        $last_update = intval(Mage::app()->loadCache('gomage_notifications_last_update'));

        if (($frequency * 60 * 60 + $last_update) > time()) {
            return false;
        }

        $timestamp = $last_update;
        if (!$timestamp) {
            $timestamp = time();
        }

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, sprintf('https://www.gomage.com/index.php/gomage_notification/index/data'));
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'sku=feed-pro&timestamp=' . $timestamp . '&ver=' . urlencode('3.7.0'));
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

            $content = curl_exec($ch);

            try {
                $result = Zend_Json::decode($content);
            } catch (\Exception $e) {
                $result = array();
            }

            if ($result && isset($result['frequency']) && ($result['frequency'] != $frequency)) {
                Mage::app()->saveCache($result['frequency'], 'gomage_notifications_frequency');
            }

            if ($result && isset($result['data'])) {
                if (!empty($result['data'])) {
                    Mage::getModel('adminnotification/inbox')->parse($result['data']);
                }
            }
        } catch (Exception $e) {
        }

        Mage::app()->saveCache(time(), 'gomage_notifications_last_update');
    }

}
