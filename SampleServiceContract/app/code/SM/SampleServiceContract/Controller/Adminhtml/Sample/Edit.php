<?php
namespace SM\SampleServiceContract\Controller\Adminhtml\Sample;

class Edit extends \SM\SampleServiceContract\Controller\Adminhtml\Sample
{
    /**
     * @var \SM\SampleServiceContract\Model\SampleFactory
     */
    protected $sampleFactory;

    /**
     * @var \SM\SampleServiceContract\Api\SampleRepositoryInterface
     */
    protected $sampleRepository;

    /**
     * Edit constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \SM\SampleServiceContract\Model\SampleFactory $sampleFactory
     * @param \SM\SampleServiceContract\Api\SampleRepositoryInterface $sampleRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \SM\SampleServiceContract\Model\SampleFactory $sampleFactory,
        \SM\SampleServiceContract\Api\SampleRepositoryInterface $sampleRepository
    ) {
        parent::__construct($context, $coreRegistry, $resultForwardFactory, $resultPageFactory);
        $this->sampleFactory = $sampleFactory;
        $this->sampleRepository = $sampleRepository;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Sample'));

        $id = $this->getRequest()->getParam('id');
        $model = $this->sampleFactory->create();

        if ($id) {
            $model = $this->sampleRepository->getById($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This item no longer exists.'));
                $this->_redirect('sm_sampleservicecontract/*');
                return;
            }
        }

        $this->_coreRegistry->register('current_sm_sampleservicecontract_sample', $model);
        $this->_initAction();
        $this->_view->renderLayout();
    }
}
