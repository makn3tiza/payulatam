<?php
/**
 * @copyright makn3tiza_
 */

namespace makn3tiza\Payulatam\Model\Client;

class Rest extends \makn3tiza\Payulatam\Model\Client
{
    /**
     * @param Rest\Config $configHelper
     * @param Rest\Order $orderHelper
     */
    public function __construct(
        Rest\Config $configHelper,
        Rest\Order $orderHelper
    ) {
        parent::__construct(
            $configHelper,
            $orderHelper
        );
    }
}
