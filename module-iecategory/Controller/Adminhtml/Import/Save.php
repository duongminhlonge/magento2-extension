<?php

namespace SM\IECategory\Controller\Adminhtml\Import;

class Save extends \SM\IECategory\Controller\Adminhtml\Import
{
    const NAME_COLUMN = 'name';
    const PATH_COLUMN = 'path';
    const DESCRIPTION_COLUMN = 'description';
    const IS_ACTIVE_COLUMN = 'is_active';
    const POSITION_COLUMN = 'position';

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $uploader;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * @var \Magento\Framework\File\Csv
     */
    protected $csvProcessor;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directoryList;

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $categoryFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;


    /**
     * Save constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $uploader
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\File\Csv $csvProcessor
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploader,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\File\Csv $csvProcessor,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context, $coreRegistry, $resultForwardFactory, $resultPageFactory);
        $this->uploader = $uploader;
        $this->filesystem = $filesystem;
        $this->csvProcessor = $csvProcessor;
        $this->directoryList = $directoryList;
        $this->categoryFactory = $categoryFactory;
        $this->storeManager = $storeManager;
    }


    public function execute()
    {
        if (isset($_FILES['import_file'])) {
            try {
                //Upload and save file csv
                $base_media_path = 'import_categories';
                $uploader = $this->uploader->create(
                    ['fileId' => 'import_file']
                );
                $uploader->setAllowCreateFolders(true);
                $uploader->setAllowedExtensions(['csv']);

                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(false);

                $mediaDirectory = $this->filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
                $result = $uploader->save(
                    $mediaDirectory->getAbsolutePath($base_media_path)
                );

                //Read and import category
                $countNewCategory = 0;
                if (file_exists($this->directoryList->getPath('media') . '/' . $base_media_path . '/' . $result['file'])) {
                    $importRawData = $this->csvProcessor->getData($this->directoryList->getPath('media') . '/' . $base_media_path . '/' . $result['file']);
                    $nameIndex = null;
                    $pathIndex = null;
                    $descriptionIndex = null;
                    $isActiveIndex = null;
                    $positionIndex = null;

                    foreach ($importRawData as $rowIndex => $dataRow) {
                        if ($rowIndex == 0) {
                            $checkNameColumn = false;
                            $checkPathColumn = false;

                            foreach ($dataRow as $columnIndex => $column) {
                                switch (strtolower($column)) {
                                    case self::NAME_COLUMN:
                                        $nameIndex = $columnIndex;
                                        $checkNameColumn = true;
                                        break;
                                    case self::PATH_COLUMN:
                                        $pathIndex = $columnIndex;
                                        $checkPathColumn = true;
                                        break;
                                    case self::DESCRIPTION_COLUMN:
                                        $descriptionIndex = $columnIndex;
                                        break;
                                    case self::IS_ACTIVE_COLUMN:
                                        $isActiveIndex = $columnIndex;
                                        break;
                                    case self::POSITION_COLUMN:
                                        $positionIndex = $columnIndex;
                                        break;
                                }
                            }

                            if (!$checkNameColumn || !$checkPathColumn) {
                                $this->messageManager->addErrorMessage(__('Import failed! Import file is not exist Name column or Path column!'));
                                $this->_redirect('sm_iecategory/*/');
                                return;
                            }
                        } else {
                            if (!isset($dataRow[$nameIndex]) && empty($dataRow[$pathIndex])) {
                                continue;
                            } else {
                                unset($pathIds);
                                unset($pathName);

                                $pathName = explode(",", $dataRow[$pathIndex]);
                                $pathIds = [];
                                $pathIds[] = 1;
                                $parentId = null;
                                $exist = false;
                                $existPath = true;

                                //Check path is exist
                                if (count($pathName) > 0) {
                                    foreach ($pathName as $name) {
                                        $collection = $this->categoryFactory->create()->getCollection()
                                            ->addAttributeToFilter('name', trim($name))
                                            ->setPageSize(1);

                                        if ($collection->getSize()) {
                                            $pathIds[] = $collection->getFirstItem()->getId();
                                            $parentId = $collection->getFirstItem()->getId();
                                        } else {
                                            //If path is wrong, break out of loop and don't create
                                            $existPath = false;
                                            continue;
                                        }
                                    }
                                }

                                if ((count($pathIds) == 1) || !$existPath) {
                                    continue;
                                }

                                $pathIds = implode('/', $pathIds);

                                //Check exist category by name
                                $nameCategory = trim($dataRow[$nameIndex]);

                                $collection = $this->categoryFactory->create()->getCollection()
                                    ->addAttributeToFilter('name', trim($nameCategory));

                                if ($collection->getSize()) {
                                    foreach ($collection as $cat) {
                                        //In case, If category is exist => do nothing
                                        if ($cat->getParentId() == $parentId) {
                                            $exist = true;
                                        }
                                    }
                                }

                                //Add new category
                                if (!$exist) {
                                    $category = $this->categoryFactory->create();
                                    $category->setName($nameCategory);
                                    if ((int)$dataRow[$isActiveIndex] == 1) {
                                        $category->setIsActive(true);
                                    } else {
                                        $category->setIsActive(false);
                                    }
                                    $category->setPath($pathIds);
                                    $category->setParentId($parentId);
                                    $category->setStoreId($this->storeManager->getStore()->getId());
                                    $category->setDescription($dataRow[$descriptionIndex]);
                                    $category->setPosition($dataRow[$positionIndex]);
                                    $category->save();
                                    $countNewCategory++;
                                }
                            }
                        }
                    }

                    $this->messageManager->addSuccessMessage(__('Import success! Total of %1 categories has been created!', $countNewCategory));
                    $this->_redirect('sm_iecategory/*/');
                    return;
                } else {
                    $this->messageManager->addErrorMessage(__('Import failed! Imported File is not exist!'));
                    $this->_redirect('sm_iecategory/*/');
                    return;
                }

            } catch (\Exception $e) {
                if ($e->getCode() == 0) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                    $this->_redirect('sm_iecategory/*/');
                    return;
                }
            }

            $this->_redirect('sm_iecategory/*/');
        }
    }
}