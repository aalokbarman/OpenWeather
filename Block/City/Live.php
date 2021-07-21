<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Toptal\OpenWeather\Block\City;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Live extends Template
{

    /**
     * Constructor
     *
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        array $data = []
    )
    {
        parent::__construct($context, $data);
    }
}

