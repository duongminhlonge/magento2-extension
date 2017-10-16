<?php
namespace SM\SampleServiceContract\Controller\Adminhtml\Sample;

class Save extends \SM\SampleServiceContract\Controller\Adminhtml\Sample
{
    /**
     * @var \SM\SampleServiceContract\Model\SampleFactory
     */
    protected $sampleFactory;

    /**
    protected $sampleRepository;

    /**
     * Save constructor.
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
        if ($this->getRequest()->getPostValue()) {
            try {
                $model = $this->sampleFactory->create();
                $data = $this->getRequest()->getPostValue();

                $id = $this->getRequest()->getParam('id');
                if ($id) {
                    $model = $this->sampleRepository->getById($id);
                    if ($id != $model->getId()) {
                        throw new \Magento\Framework\Exception\LocalizedException(__('The wrong sample is specified.'));
                    }
                }
                
                $model->setData($data);
                
                $this->sampleRepository->save($model);

                $this->messageManager->addSuccessMessage(__('You saved the sample.'));
              
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('sm_sampleservicecontract/*/edit', ['id' => $model->getId()]);
                    return;
                }
                $this->_redirect('sm_sampleservicecontract/*/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $id = (int)$this->getRequest()->getParam('id');
                if (!empty($id)) {
                    $this->_redirect('sm_sampleservicecontract/*/edit', ['id' => $id]);
                } else {
                    $this->_redirect('sm_sampleservicecontract/*/new');
                }
                return;
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('Something went wrong while saving the sample data. Please review the error log.')
                );
             
                $this->_redirect('sm_sampleservicecontract/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }

        }

        $this->_redirect('sm_sampleservicecontract/*/');
    }
}
