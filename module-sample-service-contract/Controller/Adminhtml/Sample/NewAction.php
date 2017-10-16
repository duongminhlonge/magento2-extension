<?php
namespace SM\SampleServiceContract\Controller\Adminhtml\Sample;

class NewAction extends \SM\SampleServiceContract\Controller\Adminhtml\Sample
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
