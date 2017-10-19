<?php

namespace SM\Category\Model\Category\Attribute\Backend;

class NavigationThumbnail extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{
    /**
     * @var
     */
    protected $uploaderFactory;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $fileUploaderFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var
     */
    private $imageUploader;

    /**
     * FeaturedImage constructor.
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
    ) {
        $this->filesystem = $filesystem;
        $this->fileUploaderFactory = $fileUploaderFactory;
        $this->logger = $logger;
    }

    /**
     * @return mixed
     */
    private function getImageUploader()
    {
        if ($this->imageUploader === null) {
            $this->imageUploader = \Magento\Framework\App\ObjectManager::getInstance()->get(
                'SM\Category\NavigationThumbnail'
            );
        }
        return $this->imageUploader;
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @return $this
     */
    public function beforeSave($object)
    {
        $attrCode = $this->getAttribute()->getAttributeCode();

        if (!$object->hasData($attrCode)) {
            $object->setData($attrCode, null);
        } else {
            $values = $object->getData($attrCode);
            if (is_array($values)) {
                if (isset($values[0]['name']) && isset($values[0]['tmp_name'])) {
                    $object->setData($attrCode, $values[0]['name']);
                }
            } else {
                if(!empty($values)){
                    $path = $this->filesystem->getDirectoryRead(
                        \Magento\Framework\App\Filesystem\DirectoryList::MEDIA
                    )->getAbsolutePath(
                        'catalog/category/navigation_thumbnail/'
                    );

                    //Remove image
                    unlink($path.$values);
                    $object->setData($attrCode, null);
                }
            }
        }

        return $this;
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @return $this
     */
    public function afterSave($object)
    {
        $image = $object->getData($this->getAttribute()->getName(), null);

        if ($image !== null) {
            try {
                $this->getImageUploader()->moveFileFromTmp($image);
            } catch (\Exception $e) {
                $this->logger->critical($e);
            }
        }

        return $this;
    }
}
