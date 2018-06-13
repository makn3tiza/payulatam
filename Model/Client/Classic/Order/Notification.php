<?php
/**
 * @copyright makn3tiza_
 */

namespace makn3tiza\Payulatam\Model\Client\Classic\Order;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;

class Notification
{
    /**
     * @var \makn3tiza\Payulatam\Model\Client\Classic\Config
     */
    protected $configHelper;

    /**
     * @param \makn3tiza\Payulatam\Model\Client\Classic\Config $configHelper
     */
    public function __construct(
        \makn3tiza\Payulatam\Model\Client\Classic\Config $configHelper
    ) {
        $this->configHelper = $configHelper;
    }

    public function getPayuplOrderId($request)
    {
        if (!$request->isPost()) {
            throw new LocalizedException(new Phrase('POST request is required.'));
        }
        $sig = $request->getParam('sig');
        $ts = $request->getParam('ts');
        $posId = $request->getParam('pos_id');
        $sessionId = $request->getParam('referenceCode');
        $secondKeyMd5 = $this->configHelper->getConfig('second_key_md5');
        if (md5($posId . $sessionId . $ts . $secondKeyMd5) === $sig) {
            return $sessionId;
        }
        throw new LocalizedException(new Phrase('Invalid SIG.'));
    }
}
