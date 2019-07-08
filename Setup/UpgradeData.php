<?php

namespace Creatuity\Monitor\Setup;

use Creatuity\Base\Setup\AbstractUpgradeData;
use Creatuity\Base\Setup\ModuleContextInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Class UpgradeData
 * @package Creatuity\FFL\Setup
 */
class UpgradeData extends AbstractUpgradeData
{

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    protected function upgrade_1_0_0(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->report()->printMessage('Adding exception table');


        $table = $setup->getConnection()
            ->newTable($setup->getTable('creatuity_exceptions'))
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                10,
                ['identity' => true, 'nullable' => false, 'primary' => true]
            )
            ->addColumn(
                'sapi',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false]
            )
            ->addColumn(
                'filename',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false]
            )
            ->addColumn(
                'line',
                Table::TYPE_INTEGER,
                10,
                ['nullable' => false]
            )
            ->addColumn(
                'state',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false]
            )
            ->addColumn(
                'current_count',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false]
            )
            ->addColumn(
                'total_count',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false]
            )
            ->addColumn(
                'message',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true]
            )
            ->addColumn(
                'stack',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true]
            )
            ->addColumn(
                'request',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true]
            )
            ->addColumn(
                'first_time',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => true]
            )
            ->addColumn(
                'last_time',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => true]
            )
            ->addColumn(
                'comment',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true]
            );

        $setup->getConnection()->createTable($table);

        $this->creatuity()->database()->dbConnection()->addIndex(
            $this->creatuity()->database()->dbConnection()->getTableName('creatuity_exceptions'),
            'filename_line',
            ['sapi', 'filename', 'line'],
            AdapterInterface::INDEX_TYPE_UNIQUE
        );

    }

}