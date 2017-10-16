<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SM\SampleServiceContract\Api;

use SM\SampleServiceContract\Api\Data\SampleInterface;

interface SampleRepositoryInterface
{
    public function save(SampleInterface $employees);

    public function getById($employeeId);

    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    public function delete(SampleInterface $employees);

    public function deleteById($employeeId);
}
