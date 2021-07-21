<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Toptal\OpenWeather\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface WeatherInterface extends ExtensibleDataInterface
{

    const CREATED_AT = 'created_at';
    const WEATHER_ID = 'weather_id';
    const CITY = 'city';
    const JSON_DATA = 'json_data';
    const COUNTRY = 'country';

    /**
     * Get weather_id
     * @return string|null
     */
    public function getWeatherId();

    /**
     * Set weather_id
     * @param string $weatherId
     * @return WeatherInterface
     */
    public function setWeatherId($weatherId);

    /**
     * Get city
     * @return string|null
     */
    public function getCity();

    /**
     * Set city
     * @param string $city
     * @return WeatherInterface
     */
    public function setCity($city);

    /**
     * Get country
     * @return string|null
     */
    public function getCountry();

    /**
     * Set country
     * @param string $country
     * @return WeatherInterface
     */
    public function setCountry($country);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return WeatherInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get json_data
     * @return string|null
     */
    public function getJsonData();

    /**
     * Set json_data
     * @param string $jsonData
     * @return WeatherInterface
     */
    public function setJsonData($jsonData);
}

