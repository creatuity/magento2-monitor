<?php

namespace Creatuity\Monitor\Cron;

use Creatuity\Monitor\Model\Collector;
use Magento\Framework\App\ResourceConnection;
use Magento\MessageBusLog\Model\Repository\MessageRepository;

class Watchdog
{
    /** @var ResourceConnection */
    private $resourceConnection;

    public function __construct(ResourceConnection $resourceConnection)
    {
        $this->resourceConnection = $resourceConnection;
    }

    public function execute()
    {
        //TODO encapsulate
        Collector::setCounter(
            'mcom_messages_queue',
            $this->resourceConnection->getConnection()->fetchOne('SELECT COUNT(*) FROM ' . MessageRepository::MCOM_API_MESSAGES_TABLE)
        );
    }
}
