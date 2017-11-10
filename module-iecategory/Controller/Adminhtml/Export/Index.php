<?php

namespace SM\IECategory\Controller\Adminhtml\Export;

class Index extends \SM\IECategory\Controller\Adminhtml\Export
{
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Export'));
        $this->_initAction();
        $this->_view->renderLayout();
    }
}
