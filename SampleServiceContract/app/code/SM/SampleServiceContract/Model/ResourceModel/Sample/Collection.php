<?php
namespace SM\SampleServiceContract\Model\ResourceModel\Sample;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('SM\SampleServiceContract\Model\Sample', 'SM\SampleServiceContract\Model\ResourceModel\Sample');
    }
}
