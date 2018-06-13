<?php
/**
 * @copyright makn3tiza_
 */

namespace makn3tiza\Payulatam\Model\Client\Rest;

class MethodCaller extends \makn3tiza\Payulatam\Model\Client\MethodCaller
{
    public function __construct(
        MethodCaller\Raw $rawMethod,
        \makn3tiza\Payulatam\Logger\Logger $logger
    ) {
        parent::__construct(
            $rawMethod,
            $logger
        );
    }
}
