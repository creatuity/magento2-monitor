<?php
/**
 * Copyright © 2015 Creatuity. All rights reserved.
 */
namespace Creatuity\Monitor\Block\Adminhtml;

class Exceptions extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'exceptions';
        $this->_headerText = __('Monitor Exception');
        $this->_addButtonLabel = null;
        parent::_construct();
        $this->buttonList->remove('add');
    }
}
