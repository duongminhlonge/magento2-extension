<?php

namespace SM\SampleServiceContract\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface SampleSearchResultsInterface extends SearchResultsInterface
{
    public function getItems();

    public function setItems(array $items);
}
