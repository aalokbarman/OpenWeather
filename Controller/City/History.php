<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Toptal\OpenWeather\Controller\City;

use Exception;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Json\Helper\Data;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\ScopeInterface;
use Psr\Log\LoggerInterface;
use Toptal\OpenWeather\Model\ResourceModel\Weather\CollectionFactory;
use Toptal\OpenWeather\Model\Weather;

class History extends Action
{

    protected  $resultPageFactory;
    protected  $jsonHelper;
    public  $weather;
    public $weatherCollection;


    /**
     * History constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param Curl $curl
     * @param Data $jsonHelper
     * @param Weather $weather
     * @param CollectionFactory $collectionFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ScopeConfigInterface $scopeConfig,
        Curl $curl,
        Data $jsonHelper,
        Weather $weather,
        CollectionFactory $collectionFactory,
        LoggerInterface $logger
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        $this->logger = $logger;
        $this->curl = $curl;
        $this->scopeConfig = $scopeConfig;
        $this->weather = $weather;
        $this->weatherCollection = $collectionFactory->create();
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        try {
            $city = $this->getRequest()->getPostValue("city");
            $querytype = $this->getRequest()->getPostValue("querytype");
            $dateRange = $this->getRequest()->getPostValue("dateRange");
            if ($querytype == "Live") {
                $result = $this->getCityWeather($city);
            } elseif ($querytype == "History") {
                $result = $this->getHistoryWeather($city, $dateRange);
            }
            return $this->jsonResponse($result);
        } catch (LocalizedException $e) {
            return $this->jsonResponse($e->getMessage());
        } catch (Exception $e) {
            $this->logger->critical($e);
            return $this->jsonResponse($e->getMessage());
        }
    }

    /**
     * @param $city
     * @return array
     */
    public function getCityWeather($city)
    {
        $apiKey = $this->scopeConfig->getValue('openweather/api/api_key', ScopeInterface::SCOPE_STORE);
        $apiUrl = $this->scopeConfig->getValue('openweather/api/api_url', ScopeInterface::SCOPE_STORE);
        $url = $apiUrl . "?" . http_build_query(['q' => $city, 'appid' => $apiKey, 'units' => 'metric']);

        $this->curl->get($url);

        $weatherData = $this->curl->getBody();
        $weatherObj = json_decode($weatherData, true);
        if ($weatherObj['cod'] == 200) {
            $this->weather->setCity($weatherObj['name']);
            $this->weather->setCountry($weatherObj['sys']['country']);
            $this->weather->setJsonData($weatherData);
            try {
                /*save current weather*/
                $this->weather->save();
            } catch (Exception $e) {
                echo $e;
                die;
            }
        }

        $return['current'] = $weatherObj;
        return $return;
    }

    /**
     * @param $city
     * @param $dateRange
     * @return array
     */
    public function getHistoryWeather($city, $dateRange)
    {
        $return['history'] = [];
        $from = $dateRange['from'] ? $dateRange['from'] : "1960-01-01";
        $from .= "00:00:01";

        $to = $dateRange['to'] ? $dateRange['to'] : date("Y-d-m");
        $to .= ' 23:59:59';
        $this->weatherCollection->addFieldToFilter('city', ['like' => '%' . $city . '%']);
        $this->weatherCollection->addFieldToFilter('created_at', array('from' => $from, 'to' => $to));
        $this->weatherCollection->setOrder('created_at', 'DESC');
        foreach ($this->weatherCollection as $item) {
            $return['history'][] = json_decode($item['json_data'], true);
        }
        return $return;
    }

    /**
     * Create json response
     *
     * @return ResultInterface
     */
    public function jsonResponse($response = '')
    {
        return $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($response)
        );
    }
}

