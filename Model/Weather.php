<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Toptal\OpenWeather\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Toptal\OpenWeather\Api\Data\WeatherInterface;
use Toptal\OpenWeather\Api\Data\WeatherInterfaceFactory;
use Toptal\OpenWeather\Model\ResourceModel\Weather\Collection;

class Weather extends AbstractModel
{

    protected $weatherDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'toptal_openweather_weather';

    /**
     * @param Context $context
     * @param Registry $registry
     * @param WeatherInterfaceFactory $weatherDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param ResourceModel\Weather $resource
     * @param Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        WeatherInterfaceFactory $weatherDataFactory,
        DataObjectHelper $dataObjectHelper,
        ResourceModel\Weather $resource,
        Collection $resourceCollection,
        array $data = []
    )
    {
        $this->weatherDataFactory = $weatherDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve weather model with weather data
     * @return WeatherInterface
     */
    public function getDataModel()
    {
        $weatherData = $this->getData();

        $weatherDataObject = $this->weatherDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $weatherDataObject,
            $weatherData,
            WeatherInterface::class
        );

        return $weatherDataObject;
    }
}

