<?php
namespace SM\SampleServiceContract\Block\Adminhtml\Sample\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('sm_sampleservicecontract_sample_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Sample'));
    }
}
