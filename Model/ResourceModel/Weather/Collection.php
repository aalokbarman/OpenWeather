<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Toptal\OpenWeather\Model\ResourceModel\Weather;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Toptal\OpenWeather\Model\ResourceModel\Weather;

class Collection extends AbstractCollection
{

    /**
     * @var string
     */
    protected $_idFieldName = 'weather_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Toptal\OpenWeather\Model\Weather::class,
            Weather::class
        );
    }
}

