<?php
/**
 * Copyright © 2015 Creatuity. All rights reserved.
 */
namespace Creatuity\Monitor\Block\Adminhtml;

use Creatuity\Monitor\Model\Collector;

class Metrics extends \Magento\Backend\Block\Template
{
    protected function tryGetCounter($key)
    {
        try {
            if (!isset($this->credis)) {
                $this->credis = Collector::getConnection();
            }
            return $this->credis->get($key);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getStats($prefix)
    {
        $now = (int)(time() / 350);
        $ret = [];
        for ($i = 240; $i >= 0; $i--) {
            $date = date('H:i', ($now - $i) * 350);
            $key = str_pad(base_convert($now - $i, 10, 12), 10, '0', STR_PAD_LEFT);
            $wago = str_pad(base_convert($now - $i - (12 * 12 * 12), 10, 12), 10, '0', STR_PAD_LEFT);

            $count = $this->tryGetCounter($prefix . ':' . $key);
            $total = $this->tryGetCounter($prefix . '.t:' . $key) / 1000;
            $wagoCount = $this->tryGetCounter($prefix . ':' . $wago);
            $wagoTotal = $this->tryGetCounter($prefix . '.t:' . $wago) / 1000;

            //$this->getConnection()->del($prefix . ':' . $key); $this->getConnection()->del($prefix . '.t:' . $key);

            $ret[$date] = [$count ?: 0, $count ? $total / $count : 0, $wagoCount ?: 0, $wagoCount ? $wagoTotal / $wagoCount : 0];
        }
        //var_dump($this->getConnection()->keys('*'));
        /*
        $today = substr($ts,0, 8) . '*';
        $weekAgo = base_convert(base_convert($ts, 12, 10) + (12 * 12 * 12), 10, 12);
        $weekAgo = str_pad($weekAgo, 10, '0', STR_PAD_LEFT);
        $weekAgo = substr($weekAgo,0, 8) . '*';
        */
        return $ret;
    }

    public function getStatsRelation($prefix1, $prefix2)
    {
        $now = (int)(time() / 350);
        $ret = [];
        for ($i = 240; $i >= 0; $i--) {
            $date = date('H:i', ($now - $i) * 350);
            $key = str_pad(base_convert($now - $i, 10, 12), 10, '0', STR_PAD_LEFT);
            $wago = str_pad(base_convert($now - $i - (12 * 12 * 12), 10, 12), 10, '0', STR_PAD_LEFT);

            $count1 = $this->tryGetCounter($prefix1 . ':' . $key);
            $count2 = $this->tryGetCounter($prefix2 . ':' . $key);
            $wagoCount1 = $this->tryGetCounter($prefix1 . ':' . $wago);
            $wagoCount2 = $this->tryGetCounter($prefix2 . ':' . $wago);

            $ret[$date] = [$count2 ? $count1 / $count2 : 1.0, $wagoCount2 ? $wagoCount1 / $wagoCount2 : 1.0];
        }
        return $ret;
    }

    public function getAllCounters()
    {
        return [
            'exceptions',
        ];
    }

    public function getAllTimers()
    {
        return [
            'request_mtime',
            'request_stime',
            'request_utime',
            'request_memory',
            'request_io'
        ];

        //$this->getConnection()->flushAll();
        //development only
        //.t:000158a135
        /*$ret = [];
        $all = $this->getConnection()->keys('*');
        foreach ($all as $key){
            $prefix = substr($key, 0, -11);
            if(substr($prefix, -2) != '.t') {
                $ret[$prefix] = true;
            }
        }
        return array_keys($ret);*/
    }
}
