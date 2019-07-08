<?php
/**
 * Copyright © 2015 Creatuity. All rights reserved.
 */
namespace Creatuity\Monitor\Block\Adminhtml\Exceptions;

use Magento\Backend\Model\UrlFactory;

class Edit extends \Magento\Backend\Block\Template
{
    protected $_template = 'edit-exception.phtml';

    /**
     * Model Url instance
     *
     * @var \Magento\Backend\Model\UrlInterface
     */
    protected $url;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Data\Form\FormKey
     */
    protected $formKey;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        UrlFactory $backendUrlFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\Form\FormKey $formKey,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->url = $backendUrlFactory->create();
        $this->formKey = $formKey;
        parent::__construct($context, $data);
    }

    /**
     * Initialize form
     * Add standard buttons
     * Add "Save and Continue" button
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_controller = 'adminhtml_exceptions';
        $this->_blockGroup = 'Creatuity_Monitor';

        parent::_construct();
    }

    /**
     * Getter for form header text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        $exception = $this->_coreRegistry->registry('current_creatuity_monitor_exceptions');
        if ($exception->getId()) {
            return __("Edit Item '%1'", $this->escapeHtml($exception->getName()));
        } else {
            return __('New Item');
        }
    }

    public function getException()
    {
        return $this->_coreRegistry->registry('current_creatuity_monitor_exceptions');
    }

    public function getBackUrl()
    {
        return $this->url->getUrl('creatuity_monitor/exceptions/index');
    }

    public function getSaveUrl($id)
    {
        return $this->url->getUrl('creatuity_monitor/exceptions/save', ['id' => $id]);
    }

    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }
}
