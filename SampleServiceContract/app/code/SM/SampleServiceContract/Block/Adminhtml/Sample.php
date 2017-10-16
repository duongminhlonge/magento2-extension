<?php
namespace SM\SampleServiceContract\Block\Adminhtml;

class Sample extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'sample';
        $this->_headerText = __('Sample');
        $this->_addButtonLabel = __('Add New Sample');
        parent::_construct();
    }
}
