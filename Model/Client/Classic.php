<?php
/**
 * @copyright makn3tiza_
 */

namespace makn3tiza\Payulatam\Model\Client;

class Classic extends \makn3tiza\Payulatam\Model\Client
{
    /**
     * @param Classic\Config $configHelper
     * @param Classic\Order $orderHelper
     */
    public function __construct(
        Classic\Config $configHelper,
        Classic\Order $orderHelper
    ) {
        parent::__construct(
            $configHelper,
            $orderHelper
        );
    }
}
