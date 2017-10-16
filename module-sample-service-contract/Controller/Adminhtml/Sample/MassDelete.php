<?php

namespace SM\SampleServiceContract\Controller\Adminhtml\Sample;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use SM\SampleServiceContract\Model\SampleFactory;

class MassDelete extends \SM\SampleServiceContract\Controller\Adminhtml\Sample
{
    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $filter;

    /**
     * @var \SM\SampleServiceContract\Model\ResourceModel\Sample\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \SM\SampleServiceContract\Api\SampleRepositoryInterface
     */
    protected $sampleRepository;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \SM\SampleServiceContract\Model\ResourceModel\Sample\CollectionFactory $collectionFactory,
        \SM\SampleServiceContract\Api\SampleRepositoryInterface $sampleRepository

    ) {
        parent::__construct($context, $coreRegistry, $resultForwardFactory, $resultPageFactory);
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->sampleRepository = $sampleRepository;
    }

    /**
     * @return void
     */
    public function execute()
    {
        // Get IDs of the selected class
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $sampleIds = $collection->getAllIds();

        if (!is_array($sampleIds)) {
            $this->messageManager->addErrorMessage(__('Please select one or more sample.'));
        } else {
            try {
                foreach ($sampleIds as $sampleId) {
                    $this->sampleRepository->deleteById($sampleId);
                }
                $this->messageManager->addSuccessMessage(__('Total of %1 record(s) were deleted.', count($sampleIds)));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }
}