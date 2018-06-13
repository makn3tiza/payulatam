<?php
/**
 * @copyright makn3tiza_
 */

namespace makn3tiza\Payulatam\Logger\Handler;

use Monolog\Logger;

class Exception extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * @var string
     */
    protected $fileName = '/var/log/makn3tiza/payulatam/exception.log';

    /**
     * @var int
     */
    protected $loggerType = Logger::CRITICAL;
}
