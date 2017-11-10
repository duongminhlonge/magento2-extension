<?php
namespace SM\IECategory\Controller\Adminhtml\Import;

use \Magento\Framework\App\Filesystem\DirectoryList;

class DummyData extends \SM\IECategory\Controller\Adminhtml\Import
{
    const DUMMY_DATA_DIR = 'DummyData';

    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $fileFactory;

    /**
     * @var DirectoryList
     */
    protected $directoryList;

    /**
     * @var \Magento\Framework\Component\ComponentRegistrar
     */
    protected $componentRegistrar;


    /**
     * DummyData constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param DirectoryList $directoryList
     * @param \Magento\Framework\Component\ComponentRegistrar $componentRegistrar
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        DirectoryList $directoryList,
        \Magento\Framework\Component\ComponentRegistrar $componentRegistrar
    ) {
        parent::__construct($context, $coreRegistry, $resultForwardFactory, $resultPageFactory);
        $this->fileFactory = $fileFactory;
        $this->directoryList = $directoryList;
        $this->componentRegistrar = $componentRegistrar;
    }


    public function execute()
    {
        $this->_view->loadLayout();
        $fileName = 'import_categories.csv';

        $path = $this->componentRegistrar->getPath(\Magento\Framework\Component\ComponentRegistrar::MODULE, 'SM_IECategory').'/'.self::DUMMY_DATA_DIR.'/'.$fileName;

        return $this->fileFactory->create(
            $fileName,
            file_get_contents($path),
            DirectoryList::MEDIA
        );
    }
}
