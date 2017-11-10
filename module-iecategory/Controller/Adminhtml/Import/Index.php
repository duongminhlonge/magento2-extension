<?php

namespace SM\IECategory\Controller\Adminhtml\Import;

class Index extends \SM\IECategory\Controller\Adminhtml\Import
{
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Import'));
        $this->_initAction();
        $this->_view->renderLayout();
    }
}
