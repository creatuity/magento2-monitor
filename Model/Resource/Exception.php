<?php
/**
 * Copyright © 2015 Creatuity. All rights reserved.
 */

namespace Creatuity\Monitor\Model\Resource;

class Exception extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('creatuity_exceptions', 'id');
    }
}
