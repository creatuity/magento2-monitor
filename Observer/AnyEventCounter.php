<?php
/**
 * Created by PhpStorm.
 * User: fwawrzak
 * Date: 23.01.19
 * @license http://creatuity.com/license
 * @copyright Copyright (c) 2008-2017 Creatuity Corp. (http://www.creatuity.com)
 */

namespace Creatuity\Monitor\Observer;


use Creatuity\Monitor\Model\Collector;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AnyEventCounter implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        Collector::incCounter('event.' . $observer->getEvent()->getName());
    }
}
