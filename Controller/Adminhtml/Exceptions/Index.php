<?php
/**
 * Copyright © 2015 Creatuity. All rights reserved.
 */

namespace Creatuity\Monitor\Controller\Adminhtml\Exceptions;

class Index extends \Creatuity\Monitor\Controller\Adminhtml\Exceptions
{
    /**
     * Exception list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Creatuity_Monitor::exception');
        $resultPage->getConfig()->getTitle()->prepend(__('Monitor Exception'));
        $resultPage->addBreadcrumb(__('Creatuity'), __('Creatuity'));
        $resultPage->addBreadcrumb(__('Monitor Exception'), __('Monitor Exception'));
        return $resultPage;
    }
}
