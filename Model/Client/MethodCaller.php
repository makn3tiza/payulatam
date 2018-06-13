<?php
/**
 * @copyright makn3tiza_
 */

namespace makn3tiza\Payulatam\Model\Client;

use makn3tiza\Payulatam\Model\Client\MethodCallerInterface;

class MethodCaller implements MethodCallerInterface
{
    /**
     * @var MethodCaller\RawInterface
     */
    protected $_rawMethod;

    /**
     * @var \makn3tiza\Payulatam\Logger\Logger
     */
    protected $_logger;

    /**
     * @param MethodCaller\RawInterface $rawMethod
     * @param \makn3tiza\Payulatam\Logger\Logger $logger
     */
    public function __construct(
        MethodCaller\RawInterface $rawMethod,
        \makn3tiza\Payulatam\Logger\Logger $logger
    ) {
        $this->_rawMethod = $rawMethod;
        $this->_logger = $logger;
    }

    /**
     * @param string $methodName
     * @param array $args
     * @return \stdClass|false
     */
    public function call($methodName, array $args = [])
    {
        try {
            return $this->_rawMethod->call($methodName, $args);
        } catch (\Exception $e) {
            $this->_logger->critical($e);
            return false;
        }
    }
}
