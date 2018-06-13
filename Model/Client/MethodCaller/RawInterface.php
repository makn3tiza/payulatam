<?php
/**
 * @copyright makn3tiza_
 */

namespace makn3tiza\Payulatam\Model\Client\MethodCaller;

use Magento\Framework\Exception\LocalizedException;

interface RawInterface
{
    /**
     * @param string $methodName
     * @param array $args
     * @return \stdClass
     * @throws LocalizedException
     */
    public function call($methodName, array $args = []);
}
