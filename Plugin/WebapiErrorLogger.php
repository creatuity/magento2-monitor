<?php

namespace Creatuity\Monitor\Plugin;

use Creatuity\Monitor\Model\ExceptionLog;
use Magento\Framework\Webapi\ErrorProcessor;

class WebapiErrorLogger
{
    protected $saved = false;
    public function beforeMaskException(ErrorProcessor $subject, \Exception $e)
    {
        $this->saved || ExceptionLog::saveException($e);
        $this->saved = true;
        return null;
    }
}