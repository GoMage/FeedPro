<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2020 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/license-agreement/  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.3.2
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Generator_Info
{
    protected $file_path = '';
    protected $total_records = 0;
    protected $generated_records = 0;
    protected $errors = array();
    protected $start_time = 0;

    protected $stopped = false;
    protected $started = false;
    protected $finished = false;

    public function __construct()
    {
        $this->start_time = microtime(true);
    }

    public function getData($key)
    {
        return $this->$key;
    }

    public function setData($key, $value)
    {
        $this->$key = $value;
        return $this;
    }

    public function save()
    {
        file_put_contents($this->file_path, serialize($this));
    }

    public function setError($error)
    {
        $this->errors[] = $error;
        $this->stop();
        return $this;
    }

    public function start()
    {
        $this->started = true;
        return $this;
    }

    public function stop()
    {
        $this->stopped = true;
        return $this;
    }

    public function finish()
    {
        $this->finished = true;
        return $this;
    }

    public function addGeneratedRecords($count)
    {
        $this->generated_records += $count;
        return $this;
    }

    public function inProcess()
    {
        return $this->started && !($this->finished || $this->stopped);
    }

    public function getGenerationTime()
    {
        $time = microtime(true) - $this->start_time;
        $hour = 0;
        $min  = 0;
        $sec  = 0;
        if ($time > 0) {
            $hour = (int)($time / 3600);
            $min  = (int)(($time - $hour * 3600) / 60);
            $sec  = (int)($time - $hour * 3600 - $min * 60);
        }
        return array($hour, $min, $sec);
    }

}
