<?php

namespace SM\Category\Model\Category;

use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Eav\Model\Config;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\EavValidationRules;

class DataProvider extends \Magento\Catalog\Model\Category\DataProvider
{
    /**
     * @var \SM\Category\Helper\Data
     */
    protected $helper;

    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param EavValidationRules $eavValidationRules
     * @param CategoryCollectionFactory $categoryCollectionFactory
     * @param StoreManagerInterface $storeManager
     * @param \Magento\Framework\Registry $registry
     * @param Config $eavConfig
     * @param \Magento\Framework\App\RequestInterface $request
     * @param CategoryFactory $categoryFactory
     * @param \SM\Category\Helper\Data $helper
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        EavValidationRules $eavValidationRules,
        CategoryCollectionFactory $categoryCollectionFactory,
        StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $registry,
        Config $eavConfig,
        \Magento\Framework\App\RequestInterface $request,
        CategoryFactory $categoryFactory,
        \SM\Category\Helper\Data $helper,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $eavValidationRules,
            $categoryCollectionFactory, $storeManager, $registry, $eavConfig, $request, $categoryFactory, $meta, $data);
        $this->helper = $helper;
    }


    /**
     * @param \Magento\Catalog\Model\Category $category
     * @param array $categoryData
     * @return array
     */
    protected function addUseDefaultSettings($category, $categoryData)
    {
        $data = parent::addUseDefaultSettings($category, $categoryData);

        $navigationThumbnail = \SM\Category\Helper\Data::NAVIGATION_THUMBNAIL;

        if (isset($data[$navigationThumbnail])) {
            unset($data[$navigationThumbnail]);
            $data[$navigationThumbnail][0]['name'] = $category->getData($navigationThumbnail);
            $data[$navigationThumbnail][0]['url'] = $this->helper->getNavigationThumbnailUrl(
                $category,
                $navigationThumbnail,
                'catalog/category/navigation_thumbnail/'
            );
        }

        return $data;
    }

    /**
     * @return array
     */
    protected function getFieldsMap()
    {
        $fields = parent::getFieldsMap();
        $fields['new_category_attributes'][] = \SM\Category\Helper\Data::NAVIGATION_THUMBNAIL;
        
        return $fields;
    }

}
