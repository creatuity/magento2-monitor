<?php
/**
 * Copyright © 2015 Creatuity. All rights reserved.
 */

namespace Creatuity\Monitor\Controller\Adminhtml\Exceptions;

use Creatuity\Monitor\Model\Exception;

class Save extends \Creatuity\Monitor\Controller\Adminhtml\Exceptions
{
    /** @var \Creatuity\Monitor\Model\Exception */
    protected $exception;

	public function __construct(
		\Creatuity\Monitor\Model\Exception $exception,
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\Registry $coreRegistry,
		\Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory
	) {
		parent::__construct($context, $coreRegistry, $resultForwardFactory, $resultPageFactory);
		$this->exception = $exception;
	}

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $post = $this->getRequest()->getPostValue();
        //var_dump($id,$post); die();
        $this->exception->load($id);
        $this->exception->setData('state', Exception::getStateFromString($post['action']));
        ($post['action'] == 'fixed') && $this->exception->setData('current_count', 0);
        $this->exception->save();
        $this->messageManager->addSuccess(__('State changed.'));
        $this->_redirect('creatuity_monitor/*/');
    }
}
