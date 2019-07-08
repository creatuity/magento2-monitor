<?php

namespace Creatuity\Monitor\Block\Adminhtml;

use Creatuity\Monitor\Model\Exception;
use Magento\Backend\Block\Widget\Grid\ColumnSet;

class ExceptionsColumns extends ColumnSet
{
    /**
     * @param $row Exception
     * @return string
     */
    public function getRowClass($row) {
        return 'exception-status-' . $row->getStateString();
    }


    public function getStateString() {
        return 'XXXX';
    }
}