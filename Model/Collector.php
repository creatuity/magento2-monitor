<?php
/**
 * Created by PhpStorm.
 * User: fwawrzak
 * Date: 11.12.18
 * @license http://creatuity.com/license
 * @copyright Copyright (c) 2008-2017 Creatuity Corp. (http://www.creatuity.com)
 */

namespace Creatuity\Monitor\Model;

use Magento\Customer\Model\Session;
use Magento\Framework\App\ObjectManager;

class Collector
{
    protected $timersStack = [];
    protected $timersValues = [];
    protected $countersAbsolute = [];
    protected $countersIncrement = [];
    protected $redisHost;
    protected $redisPort;
    protected $redisDbIdx;

    static protected $instance;

    private function __construct($redisHost, $redisPort, $redisDbIdx)
    {
        $this->redisHost = $redisHost;
        $this->redisPort = $redisPort;
        $this->redisDbIdx = $redisDbIdx;
    }

    public function __destruct()
    {
        if (php_sapi_name() === 'fpm-fcgi') {
            $usage = getrusage();
            $this->timersValues['request_stime'] = [1, ($usage['ru_utime.tv_sec'] * 1000000 + $usage['ru_utime.tv_usec'])];
            $this->timersValues['request_utime'] = [1, ($usage['ru_stime.tv_sec'] * 1000000 + $usage['ru_stime.tv_usec'])];
            $this->timersValues['request_memory'] = [1, $usage['ru_maxrss']];
            $this->timersValues['request_io'] = [1, $usage['ru_inblock'] + $usage['ru_oublock']];
            static::endTimer('request_mtime');
        }
        $this->tryToSaveStats();
    }

    public static function init()
    {
        if (empty(self::$instance)) {
            if (getenv('MAGENTO_CLOUD_ENVIRONMENT')) {
                self::$instance = new self('redis.internal', 6379, 7);
            } else {
                self::$instance = new self('localhost', 6379, 0);
            }
            if (php_sapi_name() === 'fpm-fcgi') {
                static::startTimer();
            }
        }
    }

    public static function startTimer()
    {
        static::init();
        array_push(static::$instance->timersStack, microtime(true));
    }

    public static function endTimer($prefix)
    {
        static::init();
        if (!isset(static::$instance->stats[$prefix])) {
            static::$instance->timersValues[$prefix] = [0, 0];
        }
        static::$instance->timersValues[$prefix][0] += 1;
        static::$instance->timersValues[$prefix][1] += (int)((microtime(true) - array_pop(static::$instance->timersStack)) * 1000000);
    }

    public static function incCounter($prefix)
    {
        static::init();
        if (!isset(static::$instance->countersIncrement[$prefix])) {
            static::$instance->countersIncrement[$prefix] = 0;
        }
        static::$instance->countersIncrement[$prefix] += 1;
    }

    public static function incUniqueCounter($prefix)
    {
        static::init();
        /** @var Session $session */
        $session = ObjectManager::getInstance()->get(Session::class);
        $uniqueUserStats = $session->getUniqueStats() ?: [];
        if (!isset($uniqueUserStats[$prefix])) {
            $uniqueUserStats[$prefix] = true;
            static::incCounter($prefix);
            $session->setUniqueStats($uniqueUserStats);
        }
    }

    public static function setCounter($prefix, $value)
    {
        static::init();
        static::$instance->countersAbsolute[$prefix] = $value;
    }

    public static function getConnection()
    {
        static::init();
        $credis = new \Credis_Client('tcp://' . static::$instance->redisHost . ':' . static::$instance->redisPort . '/persistent');
        $credis->select(static::$instance->redisDbIdx);
        return $credis;
    }

    protected function tryToSaveStats()
    {
        try {
            $credis = new \Credis_Client('tcp://' . $this->redisHost . ':' . $this->redisPort . '/persistent');
            $credis->select($this->redisDbIdx);
            $ts = ':' . $this->getTs();
            foreach ($this->timersValues as $prefix => $val) {
                $credis->incrBy($prefix . $ts, $val[0]);
                $credis->incrBy($prefix . '.t' . $ts, $val[1]);
            }
            foreach ($this->countersIncrement as $prefix => $val) {
                $credis->incrBy($prefix . $ts, $val);
            }
            foreach ($this->countersAbsolute as $prefix => $val) {
                $credis->set($prefix . $ts, $val);
            }
        } catch (\Exception $exception) {
            //ignore possible exceptions
        }
    }

    protected function getTs()
    {
        if (!isset($this->ts)) {
            $this->ts = base_convert((int)(time() / 350), 10, 12);
            $this->ts = str_pad($this->ts, 10, '0', STR_PAD_LEFT);
        }
        return $this->ts;
    }

}