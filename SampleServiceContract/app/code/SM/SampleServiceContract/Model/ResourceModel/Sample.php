<?php
namespace SM\SampleServiceContract\Model\ResourceModel;

class Sample extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('sm_data_table', 'id');
    }
}
