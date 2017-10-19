<?php

namespace SM\Category\Helper;

use Magento\Framework\App\Helper\Context;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const NAVIGATION_THUMBNAIL = "navigation_thumbnail";

    protected $storeManager;
    
    public function __construct(
        Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        parent::__construct($context);
        $this->storeManager = $storeManager;
    }


    /**
     * @param $category
     * @return bool|string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getNavigationThumbnailUrl($category, $attribute, $path)
    {
        $url = false;
        $image = $category->getData($attribute);
        if ($image) {
            if (is_string($image)) {
                $url = $this->storeManager->getStore()->getBaseUrl(
                        \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                    ) . $path . $image;
            } else {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Something went wrong while getting the image url.')
                );
            }
        }

        return $url;
    }
}
