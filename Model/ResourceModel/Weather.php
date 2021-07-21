<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Toptal\OpenWeather\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Weather extends AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('toptal_openweather_weather', 'weather_id');
    }
}

