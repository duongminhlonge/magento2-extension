<?php

namespace SM\IECategory\Controller\Adminhtml\Export;

use \Magento\Framework\App\Filesystem\DirectoryList;

class ExportCsv extends \SM\IECategory\Controller\Adminhtml\Export
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
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $categoryFactory;


    /**
     * ExportCsv constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param DirectoryList $directoryList
     * @param \Magento\Framework\Component\ComponentRegistrar $componentRegistrar
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        DirectoryList $directoryList,
        \Magento\Framework\Component\ComponentRegistrar $componentRegistrar,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory
    ) {
        parent::__construct($context, $coreRegistry, $resultForwardFactory, $resultPageFactory);
        $this->fileFactory = $fileFactory;
        $this->directoryList = $directoryList;
        $this->componentRegistrar = $componentRegistrar;
        $this->categoryFactory = $categoryFactory;
    }


    public function execute()
    {
        $this->_view->loadLayout();
        $fileName = 'export_categories.csv';
        
        $categories = $this->categoryFactory->create()
            ->getCollection()
            ->addFieldToSelect('*');
        
        //Get heading column
        $heading = [];
        foreach ($categories as $category){
            $data = $category->getData();
            foreach ($data as $key => $value){
                if(!isset($heading[$key])){
                    $heading[$key] = $key;
                }
            }
        }
        $outputFile = $this->directoryList->getPath('media').'/'.$fileName;

        if(file_exists($outputFile)){
            unlink($outputFile);
        }

        $handle = fopen($outputFile, 'w');
        fputcsv($handle, $heading);

        foreach ($categories as $category) {
            $row = [];
            foreach ($heading as $h){
                $row[] = $category->getData($h);
            }
            fputcsv($handle, $row);
        }
        
        return $this->fileFactory->create(
            $fileName,
            file_get_contents($outputFile),
            DirectoryList::MEDIA
        );
    }
}
