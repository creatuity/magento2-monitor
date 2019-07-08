<?php

namespace Creatuity\Monitor\Plugin;

use Creatuity\Monitor\Model\Collector;
use Creatuity\Monitor\Model\ExceptionLog;

class MonitorExceptions
{
    public function aroundCatchException(
        \Magento\Framework\AppInterface $subject,
        callable $proceed,
        \Magento\Framework\App\Bootstrap $bootstrap,
        \Exception $exception
    ) {
        $e = $exception;
        while ($e) {
            ExceptionLog::saveException($e);
            $e = $e->getPrevious();
        }
        return $proceed($bootstrap, $exception);
    }
}