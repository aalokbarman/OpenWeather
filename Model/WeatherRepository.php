<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Toptal\OpenWeather\Model;

use Exception;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;
use Toptal\OpenWeather\Api\Data\WeatherInterface;
use Toptal\OpenWeather\Api\Data\WeatherInterfaceFactory;
use Toptal\OpenWeather\Api\Data\WeatherSearchResultsInterfaceFactory;
use Toptal\OpenWeather\Api\WeatherRepositoryInterface;
use Toptal\OpenWeather\Model\ResourceModel\Weather as ResourceWeather;
use Toptal\OpenWeather\Model\ResourceModel\Weather\CollectionFactory as WeatherCollectionFactory;

class WeatherRepository implements WeatherRepositoryInterface
{

    private $collectionProcessor;

    protected $dataObjectHelper;

    protected $extensionAttributesJoinProcessor;

    protected $weatherFactory;

    protected $searchResultsFactory;

    protected $dataObjectProcessor;

    protected $extensibleDataObjectConverter;
    protected $resource;

    protected $dataWeatherFactory;

    private $storeManager;

    protected $weatherCollectionFactory;


    /**
     * @param ResourceWeather $resource
     * @param WeatherFactory $weatherFactory
     * @param WeatherInterfaceFactory $dataWeatherFactory
     * @param WeatherCollectionFactory $weatherCollectionFactory
     * @param WeatherSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceWeather $resource,
        WeatherFactory $weatherFactory,
        WeatherInterfaceFactory $dataWeatherFactory,
        WeatherCollectionFactory $weatherCollectionFactory,
        WeatherSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    )
    {
        $this->resource = $resource;
        $this->weatherFactory = $weatherFactory;
        $this->weatherCollectionFactory = $weatherCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataWeatherFactory = $dataWeatherFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        WeatherInterface $weather
    )
    {
        /* if (empty($weather->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $weather->setStoreId($storeId);
        } */

        $weatherData = $this->extensibleDataObjectConverter->toNestedArray(
            $weather,
            [],
            WeatherInterface::class
        );

        $weatherModel = $this->weatherFactory->create()->setData($weatherData);

        try {
            $this->resource->save($weatherModel);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the weather: %1',
                $exception->getMessage()
            ));
        }
        return $weatherModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function get($weatherId)
    {
        $weather = $this->weatherFactory->create();
        $this->resource->load($weather, $weatherId);
        if (!$weather->getId()) {
            throw new NoSuchEntityException(__('weather with id "%1" does not exist.', $weatherId));
        }
        return $weather->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        SearchCriteriaInterface $criteria
    )
    {
        $collection = $this->weatherCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            WeatherInterface::class
        );

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }

        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        WeatherInterface $weather
    )
    {
        try {
            $weatherModel = $this->weatherFactory->create();
            $this->resource->load($weatherModel, $weather->getWeatherId());
            $this->resource->delete($weatherModel);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the weather: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($weatherId)
    {
        return $this->delete($this->get($weatherId));
    }
}

