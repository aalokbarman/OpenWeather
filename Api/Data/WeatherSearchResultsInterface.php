<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Toptal\OpenWeather\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface WeatherSearchResultsInterface extends SearchResultsInterface
{

    /**
     * Get weather list.
     * @return WeatherInterface[]
     */
    public function getItems();

    /**
     * Set city list.
     * @param WeatherInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

