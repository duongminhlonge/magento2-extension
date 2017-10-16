<?php
/**
 * Copyright © 2015 SmartOSC. All rights reserved.
 */

namespace SM\SampleServiceContract\Controller\Adminhtml\Sample;

class Index extends \SM\SampleServiceContract\Controller\Adminhtml\Sample
{
    /**
     * Items list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('SM_SampleServiceContract::sample');
        $resultPage->getConfig()->getTitle()->prepend(__('Sample'));
        $resultPage->addBreadcrumb(__('Sample'), __('Sample'));
        return $resultPage;
    }
}
