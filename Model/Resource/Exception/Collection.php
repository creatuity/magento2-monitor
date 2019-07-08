<?php
/**
 * Copyright © 2015 Creatuity. All rights reserved.
 */

namespace Creatuity\Monitor\Model\Resource\Exception;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Creatuity\Monitor\Model\Exception', 'Creatuity\Monitor\Model\Resource\Exception');
    }
}
