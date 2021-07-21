<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Toptal\OpenWeather\Model\Data;

use Magento\Framework\Api\AbstractExtensibleObject;
use Toptal\OpenWeather\Api\Data\WeatherExtensionInterface;
use Toptal\OpenWeather\Api\Data\WeatherInterface;

class Weather extends AbstractExtensibleObject implements WeatherInterface
{

    /**
     * Get weather_id
     * @return string|null
     */
    public function getWeatherId()
    {
        return $this->_get(self::WEATHER_ID);
    }

    /**
     * Set weather_id
     * @param string $weatherId
     * @return WeatherInterface
     */
    public function setWeatherId($weatherId)
    {
        return $this->setData(self::WEATHER_ID, $weatherId);
    }

    /**
     * Get city
     * @return string|null
     */
    public function getCity()
    {
        return $this->_get(self::CITY);
    }

    /**
     * Set city
     * @param string $city
     * @return WeatherInterface
     */
    public function setCity($city)
    {
        return $this->setData(self::CITY, $city);
    }

    /**
     * Get country
     * @return string|null
     */
    public function getCountry()
    {
        return $this->_get(self::COUNTRY);
    }

    /**
     * Set country
     * @param string $country
     * @return WeatherInterface
     */
    public function setCountry($country)
    {
        return $this->setData(self::COUNTRY, $country);
    }

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt()
    {
        return $this->_get(self::CREATED_AT);
    }

    /**
     * Set created_at
     * @param string $createdAt
     * @return WeatherInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get json_data
     * @return string|null
     */
    public function getJsonData()
    {
        return $this->_get(self::JSON_DATA);
    }

    /**
     * Set json_data
     * @param string $jsonData
     * @return WeatherInterface
     */
    public function setJsonData($jsonData)
    {
        return $this->setData(self::JSON_DATA, $jsonData);
    }
}

