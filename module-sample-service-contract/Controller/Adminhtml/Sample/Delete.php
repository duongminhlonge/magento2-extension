<?php
namespace SM\SampleServiceContract\Controller\Adminhtml\Sample;

class Delete extends \SM\SampleServiceContract\Controller\Adminhtml\Sample
{
    /**
     * @var \SM\SampleServiceContract\Api\SampleRepositoryInterface
     */
    protected $sampleRepository;

    /**
     * Delete constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \SM\SampleServiceContract\Api\SampleRepositoryInterface $sampleRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \SM\SampleServiceContract\Api\SampleRepositoryInterface $sampleRepository
    ) {
        parent::__construct($context, $coreRegistry, $resultForwardFactory, $resultPageFactory);
        $this->sampleRepository = $sampleRepository;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $this->sampleRepository->deleteById($id);

                $this->messageManager->addSuccessMessage(__('You deleted the item.'));
                $this->_redirect('sm_sampleservicecontract/*/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('We can\'t delete item right now. Please review the log and try again.')
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_redirect('sm_sampleservicecontract/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a item to delete.'));
        $this->_redirect('sm_sampleservicecontract/*/');
    }
}
