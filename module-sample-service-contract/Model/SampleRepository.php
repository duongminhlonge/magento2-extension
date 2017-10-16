<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SM\SampleServiceContract\Model;

use SM\SampleServiceContract\Api\Data;
use SM\SampleServiceContract\Api\SampleRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use SM\SampleServiceContract\Model\ResourceModel\Sample as ResourceSample;
use SM\SampleServiceContract\Model\ResourceModel\Sample;
use SM\SampleServiceContract\Model\ResourceModel\Sample\CollectionFactory as SampleCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class SampleRepository implements SampleRepositoryInterface
{
    /**
     * @var ResourceSample
     */
    protected $resource;

    /**
     * @var SampleFactory
     */
    protected $sampleFactory;

    /**
     * @var SampleCollectionFactory
     */
    protected $sampleCollectionFactory;

    /**
     * @var Data\SampleSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var \SM\SampleServiceContract\Api\Data\SampleInterfaceFactory
     */
    protected $dataSampleFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    
    public function __construct(
        ResourceSample $resource,
        SampleFactory $sampleFactory,
        Data\SampleInterfaceFactory $dataSampleFactory,
        SampleCollectionFactory $sampleCollectionFactory,
        Data\SampleSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->sampleFactory = $sampleFactory;
        $this->sampleCollectionFactory = $sampleCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataSampleFactory = $dataSampleFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * Save Sample data
     *
     * @param \SM\SampleServiceContract\Api\Data\SampleInterface $sample
     * @return Sample
     * @throws CouldNotSaveException
     */
    public function save(\SM\SampleServiceContract\Api\Data\SampleInterface $sample)
    {
        if (empty($sample->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $sample->setStoreId($storeId);
        }
        try {
            $this->resource->save($sample);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the sample: %1',
                $exception->getMessage()
            ));
        }
        return $sample;
    }

    /**
     * Load Sample data by given Page Identity
     *
     * @param string $sampleId
     * @return Sample
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($sampleId)
    {
        $sample = $this->sampleFactory->create();
        $sample->load($sampleId);
        if (!$sample->getId()) {
            throw new NoSuchEntityException(__('Sample with id "%1" does not exist.', $sampleId));
        }
        return $sample;
    }

    /**
     * Load Sample data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \SM\SampleServiceContract\Model\Resource\Sample\Collection
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->sampleCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), false);
                    continue;
                }
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        $sample = [];
        /** @var Sample $sampleModel */
        foreach ($collection as $sampleModel) {
            $sampleData = $this->dataSampleFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $sampleData,
                $sampleModel->getData(),
                'SM\SampleServiceContract\Api\Data\SampleInterface'
            );
            $sample[] = $this->dataObjectProcessor->buildOutputDataArray(
                $sampleModel,
                'SM\SampleServiceContract\Api\Data\SampleInterface'
            );
        }
        $searchResults->setItems($sample);
        return $searchResults;
    }

    /**
     * Delete Sample
     *
     * @param \SM\SampleServiceContract\Api\Data\SampleInterface
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(\SM\SampleServiceContract\Api\Data\SampleInterface $sample)
    {
        try {
            $this->resource->delete($sample);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the sample: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * Delete Sample by given Sample Identity
     *
     * @param string $sampleId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($sampleId)
    {
        return $this->delete($this->getById($sampleId));
    }
}
