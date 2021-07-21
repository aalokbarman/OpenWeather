<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Toptal\OpenWeather\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Toptal\OpenWeather\Api\Data\WeatherInterface;
use Toptal\OpenWeather\Api\Data\WeatherSearchResultsInterface;

interface WeatherRepositoryInterface
{

    /**
     * Save weather
     * @param WeatherInterface $weather
     * @return WeatherInterface
     * @throws LocalizedException
     */
    public function save(
        WeatherInterface $weather
    );

    /**
     * Retrieve weather
     * @param string $weatherId
     * @return WeatherInterface
     * @throws LocalizedException
     */
    public function get($weatherId);

    /**
     * Retrieve weather matching the specified criteria.
     * @param SearchCriteriaInterface $searchCriteria
     * @return WeatherSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(
        SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete weather
     * @param WeatherInterface $weather
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(
        WeatherInterface $weather
    );

    /**
     * Delete weather by ID
     * @param string $weatherId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($weatherId);
}

