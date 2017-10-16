<?php
namespace SM\SampleServiceContract\Model;

use SM\SampleServiceContract\Api\Data\SampleInterface;

class Sample extends \Magento\Framework\Model\AbstractModel implements SampleInterface
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('SM\SampleServiceContract\Model\ResourceModel\Sample');
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        // TODO: Implement getContent() method.
    }

    /**
     * Set ID
     *
     * @param string $content
     * @return SampleInterface
     */
    public function setContent($content)
    {
        // TODO: Implement setContent() method.
    }
}
