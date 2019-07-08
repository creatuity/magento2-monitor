<?php
/**
 * Copyright © 2015 Creatuity. All rights reserved.
 */

namespace Creatuity\Monitor\Controller\Adminhtml\Exceptions;

class Edit extends \Creatuity\Monitor\Controller\Adminhtml\Exceptions
{

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create('Creatuity\Monitor\Model\Exception');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This exception no longer exists.'));
                $this->_redirect('creatuity_monitor/*');
                return;
            }
        }
        // set entered data if was error when we do save
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }
        $this->_coreRegistry->register('current_creatuity_monitor_exceptions', $model);
        $this->_initAction();
        $this->_view->getLayout()->getBlock('exceptions_exceptions_edit');
        $this->_view->renderLayout();
    }
}
