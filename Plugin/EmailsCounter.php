<?php
/**
 * Created by PhpStorm.
 * User: fwawrzak
 * Date: 24.01.19
 * @license http://creatuity.com/license
 * @copyright Copyright (c) 2008-2017 Creatuity Corp. (http://www.creatuity.com)
 */

namespace Creatuity\Monitor\Plugin;


use Creatuity\Monitor\Model\Collector;

class EmailsCounter
{
    public function afterSend(\Magento\Sales\Model\Order\Email\Sender $subject, $result)
    {
        if ($result) {
            $path = explode('\\', get_class($subject));
            $name = array_pop($path);
            if ($name == 'Interceptor') {
                $name = array_pop($path);
            }
            Collector::incCounter('email_sent');
            Collector::incCounter('email.' . $name);
        }
        return $result;
    }

}