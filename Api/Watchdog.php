<?php
/**
 * Created by PhpStorm.
 * User: fwawrzak
 * Date: 12.04.19
 * @license http://creatuity.com/license
 * @copyright Copyright (c) 2008-2017 Creatuity Corp. (http://www.creatuity.com)
 */
namespace Creatuity\Monitor\Api;


use Magento\Framework\App\ObjectManager;

class Watchdog
{
    /**
     * Get Status
     *
     * @api
     * @return \Creatuity\Monitor\Api\Data\Status
     */
    public function getStatus(){
        return ObjectManager::getInstance()->get(Data\Status::class);
    }
}