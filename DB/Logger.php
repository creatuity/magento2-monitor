<?php
/**
 * Created by PhpStorm.
 * User: fwawrzak
 * Date: 12.12.18
 * @license http://creatuity.com/license
 * @copyright Copyright (c) 2008-2017 Creatuity Corp. (http://www.creatuity.com)
 */

namespace Creatuity\Monitor\DB;

use Creatuity\Monitor\Model\Collector;
use Creatuity\Monitor\Model\ExceptionLog;

class Logger extends \Magento\Framework\DB\Logger\LoggerProxy
{

    /**
     * {@inheritdoc}
     */
    public function logStats($type, $sql, $bind = [], $result = null)
    {
        Collector::endTimer('db.' . $type);
        parent::logStats($type, $sql, $bind, $result);
    }

    /**
     * {@inheritdoc}
     */
    public function critical(\Exception $e)
    {
        //TODO validate if this is not duplicated
        ExceptionLog::saveException($e);
        parent::critical($e);
    }

    /**
     * {@inheritdoc}
     */
    public function startTimer()
    {
        Collector::startTimer();
        parent::startTimer();
    }

}