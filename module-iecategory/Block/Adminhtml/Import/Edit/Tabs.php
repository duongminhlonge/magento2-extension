<?php
namespace SM\IECategory\Block\Adminhtml\Import\Edit;

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
        $this->setId('sm_iecategory_import_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Import'));
    }
}
